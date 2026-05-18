document.querySelectorAll('.quantity').forEach(input => {

    input.addEventListener('change', function () {

        let cart_id = this.dataset.id;
        let quantity = this.value;

        fetch('../controllers/VendorCartController.php?action=update', {

            method: 'POST',

            headers: {
                'Content-Type':
                'application/x-www-form-urlencoded'
            },

            body:
            `cart_id=${cart_id}&quantity=${quantity}`

        })
        .then(res => res.json())
        .then(data => {

            if(data.success){
                location.reload();
            }

        });

    });

});

document.querySelectorAll('.remove-btn').forEach(btn => {

    btn.addEventListener('click', function () {

        let cart_id = this.dataset.id;

        fetch('../controllers/VendorCartController.php?action=remove', {

            method: 'POST',

            headers: {
                'Content-Type':
                'application/x-www-form-urlencoded'
            },

            body: `cart_id=${cart_id}`

        })
        .then(res => res.json())
        .then(data => {

            if(data.success){
                location.reload();
            }

        });

    });

});