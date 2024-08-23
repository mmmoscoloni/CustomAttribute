require(['jquery'], function($) {
    $(document).ready(function() {
        var specialAttributeInput = $('#special_attribute_input');
        var errorMessage = $('<p class="error-message" style="color: red; display: none;"></p>');
        specialAttributeInput.after(errorMessage);

        specialAttributeInput.on('input', function() {
            var value = $(this).val();
            if (value.length < 3 || value.trim() === '') {
                errorMessage.text('The special attribute must be at least 3 characters long.');
                errorMessage.show();
            } else {
                errorMessage.hide();
            }
        });
    });
});
