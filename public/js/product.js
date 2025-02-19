$(document).ready(function () {

    var discountType = document.getElementById("discount_type");
    var discountDiv = document.getElementById("discount_value_div");
    
    if(discountType.value == "1" || discountType.value == "2") {
        discountDiv.classList.remove("d-none");
    } else {
        discountDiv.classList.add("d-none");
    }
})
function handleDiscountChange(selectElement) {
    var selectedValue = selectElement.value;
    var discountDiv = document.getElementById("discount_value_div");
    var discountInput = document.getElementById("discount");

    if (selectedValue == "1" || selectedValue == "2") {
        discountDiv.classList.remove("d-none"); 
        discountInput.setAttribute("required", "required"); 
    } else {
        discountDiv.classList.add("d-none");
        discountInput.removeAttribute("required");
    }
}