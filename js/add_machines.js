function validateForm() {
    var productName = document.getElementById("productName").value;
    var category = document.getElementById("category").value;
    var description = document.getElementById("description").value;
    var capacity = document.getElementById("capacity").value;
    var quantity = document.getElementById("quantity").value;
    var actionBuy = document.getElementById("buy").checked;
    var actionRent = document.getElementById("rent").checked;
    var productPrice = document.getElementById("productPrice").value;
    var farePerHour = document.getElementById("farePerHour").value;
    var farePerDay = document.getElementById("farePerDay").value;
    var image = document.getElementById("image").value;
    var userLog = document.getElementById("userLog").value;

    // Check if any required field is empty
    if (
        productName === "" ||
        category === "" ||
        description === "" ||
        capacity === "" ||
        quantity === "" ||
        (!actionBuy && !actionRent) ||
        (actionBuy && (productPrice === "")) ||
        (actionRent && (farePerHour === "" || farePerDay === "")) ||
        image === "" ||
        userLog === ""
    ) {
        alert("Please fill in all required fields");
        return false; // prevent form submission
    }

    // Additional validation logic can be added here if needed

    return true; // allow form submission
}
function validateProductName() {
    var productNameInput = document.getElementById("productName");
    var productNameError = document.getElementById("productNameError");

    // Validate Product Name
    var productName = productNameInput.value.trim();

    if (productName === "") {
        productNameError.textContent = "Product Name cannot be empty.";
    } else {
        productNameError.textContent = ""; // Clear error message
    }
}