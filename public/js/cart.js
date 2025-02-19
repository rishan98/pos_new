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
                    console.log(data);
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
                $("#customer-name-text").text(
                    data.customer.first_name + " " + data.customer.last_name
                );
                $("#verified-customer-id").val(data.customer.id);
            } else {
                $("#customer-name-text").text("No valid Customer Selected");
                $("#verified-customer-id").val("");
            }
        },
    });
}

// Function to add a product to the cart
function addProductToCart(productId) {
    $.ajax({
        url: "/admin/add-to-cart",
        type: "GET",
        data: {
            productId: productId,
        },
        success: function (data) {
            if (data.status) {
                const tbody = document.getElementById("product-table-body");
                const selectedProduct = data.product;

                // Check if the product already exists in the table
                const existingRow = [...tbody.rows].find(
                    (row) => row.cells[0].innerText === selectedProduct.name
                );

                if (existingRow) {
                    // If the product exists, increment the quantity
                    const qtyInput = existingRow.querySelector(".qty");
                    const priceElement = existingRow.querySelector(".price");
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
                    priceElement.innerText = `Rs ${
                        selectedProduct.price * parseInt(qtyInput.value)
                    }`;

                    // Add validation on quantity input change
                    qtyInput.addEventListener("input", function () {
                        validateQuantity(qtyInput, selectedProduct);
                    });
                } else {
                    // If the product does not exist, create a new row
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>
                            <input type="hidden" name="products[]" value="${selectedProduct.id}">
                            ${selectedProduct.name}
                        </td>
                        <td>
                            <input type="number" name="quantities[]" class="form-control form-control-sm qty w-25 ms-5" value="1">
                            <button class="btn btn-danger btn-sm delete-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                        <td class="text-end price">Rs ${selectedProduct.price}</td>
                    `;
                    tbody.appendChild(row);

                    // Add event listener to the delete button
                    row.querySelector(".delete-btn").addEventListener(
                        "click",
                        function () {
                            row.remove();
                        }
                    );

                    // Add validation on quantity input change
                    const qtyInput = row.querySelector(".qty");
                    qtyInput.addEventListener("input", function () {
                        validateQuantity(qtyInput, selectedProduct);
                    });
                }
            } else {
                alert(data.error);
            }
        },
    });
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
    priceElement.innerText = `Rs ${(
        product.price * parseInt(qtyInput.value)
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
