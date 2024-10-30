jQuery(document).ready(function ($) {
    var template = {
        addon: wp.template('product-addon'),
        option: wp.template('product-addon-option')
    };
    var $addons = $('.product-addons');
    var lenght = 0;

    $('#product-addons').on('click', 'input.add-addon', function () {
        var values = {
            name: '',
            type: '',
            description: '',
        }

        if(saved[lenght]) {
            values = {
                name: saved[lenght].name,
                type: saved[lenght].type,
                description: saved[lenght].description,
            }
        }
        
        $addons.append(template.addon({
            id: lenght,
            name: {
                id: 'product_addons_'+lenght+'_name',
                name: 'product[addons]['+lenght+'][name]',
                value: values.name
            },
            type: {
                id: 'product_addons_'+lenght+'_type',
                name: 'product[addons]['+lenght+'][type]',
                value: values.type
            },
            description: {
                id: 'product_addons_'+lenght+'_description',
                name: 'product[addons]['+lenght+'][description]',
                value: values.description
            }
        }));

        $addons.find('fieldset').last().find('input.add-option').trigger('click');
        $('#product_addons_'+lenght+'_type').val(values.type).trigger('change');
        lenght++;
    });

    $('#product-addons').on('click', 'input.add-option', function () {
        var $fieldset = $(this).closest('fieldset');
        var addonId = $fieldset.data('id');
        var lastId = $fieldset.find('.option').last().data('id');

        if(typeof lastId == 'undefined') {
            lastId = 0;
        } else {
            lastId++;
        }

        var values = {
            name: '',
            price: '',
            multiple: 0,
        }

        if(saved[addonId] && saved[addonId].options[lastId]) {
            values = {
                name: saved[addonId].options[lastId].name,
                price: saved[addonId].options[lastId].price,
                multiple: saved[addonId].options[lastId].multiple ? saved[addonId].options[lastId].multiple == 'on' : 0,
            }
        }

        $fieldset.find('.options').append(template.option({
            id: lastId,
            name: {
                name: 'product[addons]['+addonId+'][options]['+lastId+'][name]',
                value: values.name
            },
            price: {
                name: 'product[addons]['+addonId+'][options]['+lastId+'][price]',
                value: values.price
            },
            multiple: {
                id: 'product_addons_'+addonId+'_options_'+lastId+'_multiple',
                name: 'product[addons]['+addonId+'][options]['+lastId+'][multiple]',
            }
        })).ready(function() {
            $('#product_addons_'+addonId+'_options_'+lastId+'_multiple').prop('checked', values.multiple);
        });
    });

    $('#product-addons').on('click', 'input.remove-option', function () {
        $(this).closest('.option').remove();
    });

    $('#product-addons').on('click', 'input.remove-addon', function () {
        $(this).closest('fieldset').remove();
    });

    $('#product-addons').on('change', 'select.addon-type', function() {
        var value = $(this).val();
        if(value == 'checkbox') {
            $(this).closest('fieldset').find('div.multiple').show();
        } else {
            $(this).closest('fieldset').find('div.multiple').hide();
            $(this).closest('fieldset').find('input.multiple').prop('checked', false);
        }
    })

    // Init saved add-ons forms.
    for(var addon in saved) {
        $('#product-addons').find('input.add-addon').trigger('click');
        for(var i = 0; i < saved[addon].options.length - 1; i++) {
            $addons.find('fieldset').last().find('input.add-option').trigger('click');
        }
    }
});