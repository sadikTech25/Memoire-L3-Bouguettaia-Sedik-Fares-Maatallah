$(document).ready(function() {
  // Handle "Add Resource" button click
  $(".add-resource-btn").click(function() {
    // Clone the last resource-group element
    var newResourceGroup = $(".resource-group:last").clone();

    // Append the cloned element after the last resource-group
    newResourceGroup.insertAfter(".resource-group:last");
    
    // Set the selected value of the cloned select element to the selected value of the original select element
    var originalSelect = $(".resource-group:last").prev().find("select");
    var clonedSelect = newResourceGroup.find("select");
    clonedSelect.val(originalSelect.val());
  });
});
