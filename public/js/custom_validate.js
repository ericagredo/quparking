$('#FormControl').bootstrapValidator({
    excluded: [':disabled'],
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {
        firstname: {
            validators: {
                notEmpty: {
                    message: 'The Firstname is required'
                }
            }
        },
        last_name: {
            validators: {
                notEmpty: {
                    message: 'The LastName is required'
                }
            }
        },
        email_address: {
            validators: {
                notEmpty: {
                    message: 'The Email Address is required'
                }, emailAddress: {
                    message: 'Please supply a valid email address'
                }
            }
        }, password: {
            validators: {
                notEmpty: {
                    message: 'The Password is required'
                },
                /*identical: {
                    field: 'cnfpassword',
                    message: 'The password and its confirm are not the same'
                },*/
            }
        },
        cnfpassword: {
            validators: {
                notEmpty: {
                    message: 'The Confirm Password is required'
                },
                identical: {
                    field: 'password',
                    message: 'The password and its confirm are not the same'
                },
            }
        },
    }
});

