document.addEventListener('DOMContentLoaded', function () {

	const modal = document.querySelector('.cwfw-modal-overlay');

	const message = document.querySelector('.cwfw-message');


	let currentButton = null;

	/*
	|--------------------------------------------------------------------------
	| Open modal
	|--------------------------------------------------------------------------
	*/

	document.addEventListener('click', function (event) {

		const button = event.target.closest('.cwfw-withdraw-button');

		if (button) {

			event.preventDefault();

			currentButton = button;

			modal.style.display = 'flex';


			return;

		}

		/*
		|--------------------------------------------------------------------------
		| Cancel
		|--------------------------------------------------------------------------
		*/

		if (event.target.closest('.cwfw-cancel')) {

			modal.style.display = 'none';

			return;

		}

		/*
		|--------------------------------------------------------------------------
		| Close when clicking outside modal
		|--------------------------------------------------------------------------
		*/

		if (event.target.classList.contains('cwfw-modal-overlay')) {

			modal.style.display = 'none';

			return;

		}

		/*
		|--------------------------------------------------------------------------
		| Submit (temporar)
		|--------------------------------------------------------------------------
		*/

		/*
|--------------------------------------------------------------------------
| Submit request
|--------------------------------------------------------------------------
*/

if (event.target.closest('.cwfw-submit')) {

    const selectedProducts = [];

    document.querySelectorAll('.cwfw-product-checkbox:checked').forEach(function (checkbox) {

        selectedProducts.push(checkbox.value);

    });

    if (!selectedProducts.length) {

        alert(cwfw.i18n.select_product);

        return;

    }

    currentButton.classList.add('disabled');

    fetch(cwfw.ajax_url, {

        method: 'POST',

        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },

        body: new URLSearchParams({

            action: 'cwfw_submit',

            nonce: cwfw.nonce,

            order_id: currentButton.dataset.orderId,

            products: JSON.stringify(selectedProducts)

        })

    })

    .then(function (response) {

		if (!response.ok) {
	
			throw new Error('HTTP error');
	
		}
	
		return response.json();
	
	})

    .then(response => {

        if (response.success) {

            modal.style.display = 'none';

            currentButton.remove();

            if (message) {

				message.innerHTML =
					'<p class="cwfw-success"><strong>' +
					cwfw.i18n.success +
					'</strong></p>';
			
			}

        } else {

            alert(cwfw.i18n.submit_error);

            currentButton.classList.remove('disabled');

        }

    })

    .catch(function (error) {

		console.error(error);
	
		alert(cwfw.i18n.unexpected_error);
	
		currentButton.classList.remove('disabled');
	
	});

}

	});

	/*
|--------------------------------------------------------------------------
| Close modal with ESC key
|--------------------------------------------------------------------------
*/

document.addEventListener('keydown', function (event) {

    if (event.key === 'Escape' && modal.style.display === 'flex') {

        modal.style.display = 'none';

    }

});

});