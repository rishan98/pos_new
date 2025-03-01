$(document).ready(function () {
    $("#search-customer").on("input", function () {
        let query = $(this).val();
        if (query.length > 1) {
            $.ajax({
                url: "/admin/search-customers",
                type: "GET",
                data: {
                    query: query,
                },
                success: function (data) {
                    let suggestions = "";
                    if (data.customers.length > 0) {
                        data.customers.forEach(function (customer) {
                            suggestions += `
                                <a href="#" class="dropdown-item customer-suggestion" data-id="${customer.id}">${customer.first_name} ${customer.last_name}</a>
                            `;
                        });
                    } else {
                        suggestions =
                            '<span class="dropdown-item text-muted">No results found</span>';
                    }
                    $("#customer-suggestions")
                        .html(suggestions)
                        .addClass("show");
                },
            });
        } else {
            $("#customer-suggestions").removeClass("show");
        }
    });

    // Handle suggestion click
    $(document).on("click", ".customer-suggestion", function (e) {
        e.preventDefault();
        let customerName = $(this).text();
        $("#search-customer").val(customerName);
        $("#customer-suggestions").removeClass("show");
    });

    // Hide suggestions when clicking outside
    $(document).on("click", function (e) {
        if (!$(e.target).closest(".position-relative").length) {
            $("#customer-suggestions").removeClass("show");
        }
    });
});

$(document).ready(function () {
    $("#search-product-input").on("input", function () {
        let searchKey = $(this).val();

        $.ajax({
            url: "/admin/cart",
            type: "GET",
            data: {
                searchKey: searchKey,
            },
            success: function (data) {
                if (data.status) {
                    $("#product-list-content").html(data.productListHtml);
                } else {
                    $("#product-list-content").html(
                        "<p>No products found.</p>"
                    );
                }
            },
        });
    });
});

function verifyCustomer() {
    let customerName = $("#search-customer").val();
    if (customerName === "") {
        alert("Please select a customer.");
        return false;
    }
    $.ajax({
        url: "/admin/verify-customer",
        type: "GET",
        data: {
            customerName: customerName,
        },
        success: function (data) {
            if (data.status) {
                $("#search-customer").val("");
                $("#customer-name-text").html(
                    data.customer.first_name +
                        " " +
                        data.customer.last_name +
                        ' <button type="button" class="btn btn-sm btn-danger ml-2" id="remove-customer" onclick="removeSelectedCustomer()">Remove</button>'
                );
                $("#verified_customer_id").val(data.customer.id);
            } else {
                $("#customer-name-text").text("No valid Customer Selected");
                $("#verified_customer_id").val("");
            }
        },
    });
}

function removeSelectedCustomer() {
    $("#customer-name-text").html("No Selected Customer");
    $("#verified_customer_id").val("");
}

function updateCheckoutButton() {
    const checkoutBtn = document.getElementById("checkout-btn");
    const hasItems =
        document.querySelectorAll("#product-table-body tr").length > 0;

    // Enable if there are items, otherwise disable
    checkoutBtn.disabled = !hasItems;
}

// Function to add a product to the cart
function addProductToCart(productId) {
    $.ajax({
        url: "/admin/add-to-cart",
        type: "GET",
        data: { productId: productId },
        success: function (data) {
            if (data.status) {
                const tbody = document.getElementById("product-table-body");
                const selectedProduct = data.product;

                // Check if the product already exists in the table
                const existingRow = [...tbody.rows].find(
                    (row) => row.cells[0].dataset.productId === selectedProduct.id.toString()
                );

                if (existingRow) {
                    const qtyInput = existingRow.querySelector(".qty");
                    const priceElement = existingRow.querySelector(".price");
                    const discountElement =
                        existingRow.querySelector(".discount");

                    qtyInput.value = parseInt(qtyInput.value) + 1;

                    if (
                        qtyInput.value >
                        selectedProduct.inventory.master_quantity
                    ) {
                        qtyInput.value =
                            selectedProduct.inventory.master_quantity;
                        alert(
                            "Available quantity is " +
                                selectedProduct.inventory.master_quantity
                        );
                    }

                    priceElement.innerText = (
                        selectedProduct.price * parseInt(qtyInput.value)
                    ).toFixed(2);
                    discountElement.innerText = (
                        selectedProduct.discount_value *
                        parseInt(qtyInput.value)
                    ).toFixed(2);

                    qtyInput.addEventListener("input", function () {
                        validateQuantity(qtyInput, selectedProduct);
                        updateTotals();
                        updateCheckoutButton(); // Check checkout button state on quantity change
                    });
                } else {
                    const maxLength = 20;
                    const productName = selectedProduct.name.length > maxLength 
                        ? selectedProduct.name.slice(0, maxLength) + '...' 
                        : selectedProduct.name;
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td data-product-id="${selectedProduct.id}">
                            <input type="hidden" name="products[]" value="${
                                selectedProduct.id
                            }">
                            ${productName}
                        </td>
                        <td>
                            <input type="number" name="quantities[]" class="form-control form-control-sm qty w-25 ms-5" value="1">
                            <button class="btn btn-danger btn-sm delete-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                        <td class="discount">${selectedProduct.discount_value.toFixed(
                            2
                        )}</td>
                        <td class="text-end price">${selectedProduct.price.toFixed(
                            2
                        )}</td>
                    `;

                    const hiddenDiscount = document.createElement("input");
                    hiddenDiscount.type = "hidden";
                    hiddenDiscount.name = "discount[]";
                    hiddenDiscount.value = selectedProduct.discount_value;
                    row.appendChild(hiddenDiscount);

                    const hiddenPrice = document.createElement("input");
                    hiddenPrice.type = "hidden";
                    hiddenPrice.name = "price[]";
                    hiddenPrice.value = selectedProduct.price;
                    row.appendChild(hiddenPrice);
                    
                    tbody.appendChild(row);

                    row.querySelector(".delete-btn").addEventListener(
                        "click",
                        function () {
                            row.remove();
                            updateTotals();
                            updateCheckoutButton(); // Check checkout button state after deleting item
                        }
                    );

                    const qtyInput = row.querySelector(".qty");
                    qtyInput.addEventListener("input", function () {
                        validateQuantity(qtyInput, selectedProduct);
                        updateTotals();
                        updateCheckoutButton(); // Check checkout button state on quantity change
                    });
                }

                updateTotals();
                updateCheckoutButton(); // Check checkout button state after adding item
            } else {
                alert(data.error);
            }
        },
    });
}

function updateTotals() {
    let subTotal = 0;
    let totalDiscount = 0;
    let totalAmount = 0;

    document.querySelectorAll("#product-table-body tr").forEach((row) => {
        const qty = parseInt(row.querySelector(".qty").value);
        const price = parseFloat(row.querySelector(".price").innerText);
        const discount = parseFloat(row.querySelector(".discount").innerText);

        subTotal += price;
        totalDiscount += discount;
    });

    totalAmount = subTotal - totalDiscount;

    // Update the total amount and total discount in the UI
    document.getElementById("sub-total").innerText =
        "Rs." + subTotal.toFixed(2);
    document.getElementById("total-discount").innerText =
        "Rs." + totalDiscount.toFixed(2);
    document.getElementById("total-amount").innerText =
        "Rs." + totalAmount.toFixed(2);

    // Update the hidden input fields
    document.getElementById("hidden-total-amount").value =
    totalAmount.toFixed(2);
    document.getElementById("hidden-total-discount").value =
        totalDiscount.toFixed(2);
}

function validateQuantity(qtyInput, product) {
    const maxQuantity = product.inventory.master_quantity;

    if (isNaN(qtyInput.value) || qtyInput.value <= 0) {
        alert("Quantity must be a positive number.");
        qtyInput.value = 1;
    }

    // Ensure the quantity does not exceed the maximum available
    if (parseInt(qtyInput.value) > maxQuantity) {
        alert("Available quantity is " + maxQuantity);
        qtyInput.value = maxQuantity;
    }

    // Update the price based on the adjusted quantity
    const row = qtyInput.closest("tr");
    const priceElement = row.querySelector(".price");
    const discountElement = row.querySelector(".discount");
    priceElement.innerText = `${(
        product.price * parseInt(qtyInput.value)
    ).toFixed(2)}`;

    discountElement.innerText = `${(
        product.discount_value * parseInt(qtyInput.value)
    ).toFixed(2)}`;
}

function searchBarcode() {
    let searchKey = $("#search-barcode").val();
    $.ajax({
        url: "/admin/search-barcode",
        type: "GET",
        data: {
            searchKey: searchKey,
        },
        success: function (data) {
            if (data.status) {
                addProductToCart(data.product.id);
                $("#search-barcode").val("");
            } else {
                alert(data.error);
                $("#search-barcode").val("");
            }
        },
    });
}

document.getElementById("checkout-btn").addEventListener("click", function () {
    if (!this.disabled) {
        // Update modal values
        document.getElementById("modal-total-amount").innerText =
            document.getElementById("total-amount").innerText;

        // Show the Bootstrap modal
        var myModal = new bootstrap.Modal(
            document.getElementById("checkoutModal"),
            {
                keyboard: false,
            }
        );
        myModal.show();
    }
});

function clearCart() {
    document.getElementById("product-table-body").innerHTML = "";
    document.getElementById("total-amount").innerText = "Rs. 0.00";
    document.getElementById("total-discount").innerText = "Rs. 0.00";
    document.getElementById("sub-total").innerText = "Rs. 0.00";
    updateCheckoutButton(); // Disable checkout button
}

updateCheckoutButton();

document
    .getElementById("confirm-checkout")
    .addEventListener("click", function () {
        // Get selected payment status
        const paymentStatus = document.getElementById("payment_status").value;
        document.getElementById("hidden-payment-status").value = paymentStatus;

        // Get the entered paid amount (only if "Partially Paid" is selected)
        if (paymentStatus === "1") {
            const paidAmount = document.getElementById(
                "partial_payment_amount"
            ).value;
            document.getElementById("hidden-paid-amount").value = paidAmount;
        } else {
            document.getElementById("hidden-paid-amount").value = 0;
        }

        // Submit the form
        document.getElementById("order-form").submit();
    });

document
    .getElementById("payment_status")
    .addEventListener("change", function () {
        const partialPaymentContainer = document.getElementById(
            "partial-payment-container"
        );
        if (this.value === "1") {
            partialPaymentContainer.style.display = "block";
        } else {
            partialPaymentContainer.style.display = "none";
        }
    });
