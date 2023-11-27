function LoadQRCode(prefix) {
    let inputAccountId, inputTransactionCurrency, inputCountryCode, inputTransactionAmount, inputMessage, divVietqrImage

    inputAccountId = document.getElementById(prefix + 'account_id')
    inputTransactionCurrency = document.getElementById(prefix + 'transaction_currency')
    inputCountryCode = document.getElementById(prefix + 'country_code')
    inputTransactionAmount = document.getElementById(prefix + 'transaction_amount')
    inputMessage = document.getElementById(prefix + 'message')
    divVietqrImage = document.getElementById(prefix + 'vietqr-image')

    if (inputAccountId.value.length === 0) {
        return;
    }
    if (inputTransactionCurrency.value.length === 0) {
        return;
    }
    if (inputCountryCode.value.length === 0) {
        return;
    }
    if (inputTransactionAmount.value.length === 0) {
        return;
    }

    var formData = new FormData()
    formData.append("account_id", inputAccountId.value)
    formData.append("transaction_currency", inputTransactionCurrency.value)
    formData.append("country_code", inputCountryCode.value)
    formData.append("transaction_amount", inputTransactionAmount.value)
    formData.append("message", inputMessage.value)

    var xhr = new XMLHttpRequest()
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            const response = JSON.parse(xhr.responseText)
            if (xhr.status == 200) {
                divVietqrImage.innerHTML = ''
                img = document.createElement('img')
                img.src = response.qr
                divVietqrImage.appendChild(img)
            } else {
                divVietqrImage.innerHTML = response.data
            }
        }
    }
    xhr.open("POST", `${location.origin}/api/vietqr`, true)
    xhr.send(formData)
}

function GetConsumerAccountInformation(ctrl, id, prefix) {
    if (ctrl && ctrl.files[0]) {
        const file = ctrl.files[0]

        var formData = new FormData()
        formData.append("image", file)

        var xhr = new XMLHttpRequest()
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                const resultContainerId = prefix + 'consumer_account_information_data'
                const resultContainer = document.getElementById(resultContainerId)
                const response = JSON.parse(xhr.responseText)
                if (xhr.status == 200) {
                    resultContainer.innerHTML = ''
                    let input = document.getElementById(id)
                    if (undefined === input || null === input) {
                        input = document.createElement('input')
                        input.id = id
                        input.type = 'hidden'
                        resultContainer.appendChild(input)
                    }
                    input.value = response.data.consumer_account_information
                } else {
                    resultContainer.innerHTML = response.data
                }
            }
        }
        xhr.open("POST", `${location.origin}/api/consumer-account-information`, true)
        xhr.send(formData)
    }
}
