<?php
session_start();
require 'database/connect_db.php';

header('Content-Type: application/json');

if (!isset($_POST['product_id'])) {
    echo json_encode(['success' => false, 'message' => 'Product ID is required']);
    exit();
}

$product_id = intval($_POST['product_id']);

// Fetch product details
$product_query = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($product_query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// Fetch customization options
$options_query = "SELECT * FROM customization_options WHERE product_id = ?";
$stmt = $conn->prepare($options_query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$options = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$html = '
<div class="customization-container">
    <div class="row">
        <div class="col-md-6">
            <div class="product-preview">
                <img src="' . $product['image_path'] . '" alt="' . $product['product_name'] . '" class="img-fluid preview-image">
                <div class="preview-overlay"></div>
            </div>
        </div>
        <div class="col-md-6">
            <h5>' . $product['product_name'] . '</h5>
            <p class="text-muted">' . $product['description'] . '</p>
            <form id="customizationForm">
                <input type="hidden" name="product_id" value="' . $product_id . '">';

foreach ($options as $option) {
    $html .= '<div class="mb-3">';
    $html .= '<label class="form-label">' . $option['option_name'];
    if ($option['additional_cost'] > 0) {
        $html .= ' (+₱' . number_format($option['additional_cost'], 2) . ')';
    }
    if ($option['is_required']) {
        $html .= ' <span class="text-danger">*</span>';
    }
    $html .= '</label>';

    switch ($option['option_type']) {
        case 'color':
            $colors = explode(',', $option['option_values']);
            $html .= '<div class="color-options">';
            foreach ($colors as $color) {
                $html .= '
                <div class="color-option">
                    <input type="radio" name="option_' . $option['option_id'] . '" 
                           value="' . trim($color) . '" class="color-radio" 
                           id="color_' . $option['option_id'] . '_' . trim($color) . '"
                           ' . ($option['is_required'] ? 'required' : '') . '>
                    <label for="color_' . $option['option_id'] . '_' . trim($color) . '" 
                           class="color-label" style="background-color: ' . trim($color) . '">
                    </label>
                </div>';
            }
            $html .= '</div>';
            break;

        case 'text':
            $html .= '
            <input type="text" class="form-control" 
                   name="option_' . $option['option_id'] . '" 
                   placeholder="Enter text for engraving"
                   ' . ($option['is_required'] ? 'required' : '') . '>';
            break;

        case 'size':
            $sizes = explode(',', $option['option_values']);
            $html .= '<select class="form-select" name="option_' . $option['option_id'] . '"
                             ' . ($option['is_required'] ? 'required' : '') . '>';
            $html .= '<option value="">Select Size</option>';
            foreach ($sizes as $size) {
                $html .= '<option value="' . trim($size) . '">' . trim($size) . '</option>';
            }
            $html .= '</select>';
            break;

        case 'material':
            $materials = explode(',', $option['option_values']);
            $html .= '<div class="material-options">';
            foreach ($materials as $material) {
                $html .= '
                <div class="form-check">
                    <input type="radio" class="form-check-input" 
                           name="option_' . $option['option_id'] . '" 
                           value="' . trim($material) . '" 
                           id="material_' . $option['option_id'] . '_' . trim($material) . '"
                           ' . ($option['is_required'] ? 'required' : '') . '>
                    <label class="form-check-label" 
                           for="material_' . $option['option_id'] . '_' . trim($material) . '">
                        ' . trim($material) . '
                    </label>
                </div>';
            }
            $html .= '</div>';
            break;
    }
    $html .= '</div>';
}

$html .= '
                <div class="text-end mt-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-brown">Save Customization</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
.color-options {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.color-option {
    position: relative;
}

.color-radio {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.color-label {
    display: block;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid #ddd;
    cursor: pointer;
    transition: all 0.3s;
}

.color-radio:checked + .color-label {
    border-color: #9D4D36;
    transform: scale(1.1);
}

.material-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.product-preview {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 20px;
}

.preview-image {
    width: 100%;
    border-radius: 10px;
}

.preview-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.1);
    pointer-events: none;
}
</style>';

echo json_encode([
    'success' => true,
    'html' => $html,
    'product' => $product,
    'options' => $options
]); 