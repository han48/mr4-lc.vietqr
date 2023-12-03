function LoadQRCode(prefix) {
    let inputAccountId, inputTransactionCurrency, inputCountryCode, inputTransactionAmount, inputTransactionId, inputMessage, divVietqrImage

    inputAccountId = document.getElementById(prefix + 'account_id')
    inputTransactionCurrency = document.getElementById(prefix + 'transaction_currency')
    inputCountryCode = document.getElementById(prefix + 'country_code')
    inputTransactionAmount = document.getElementById(prefix + 'transaction_amount')
    inputTransactionId = document.getElementById(prefix + 'transaction_id')
    inputMessage = document.getElementById(prefix + 'message')
    divVietqrImage = document.getElementById(prefix + 'vietqr-image')

    if (inputAccountId.value.length === 0) {
        return
    }
    if (inputTransactionCurrency.value.length === 0) {
        return
    }
    if (inputCountryCode.value.length === 0) {
        return
    }
    if (inputTransactionAmount.value.length === 0) {
        return
    }

    var formData = new FormData()
    formData.append("account_id", inputAccountId.value)
    formData.append("transaction_currency", inputTransactionCurrency.value)
    formData.append("country_code", inputCountryCode.value)
    formData.append("transaction_amount", inputTransactionAmount.value)
    formData.append("transaction_id", inputTransactionId.value)
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

                    const inputIds = [
                        '_bank-code',
                        '_bank-name',
                        '_bank-shortName',
                        '_bank-bin',
                        '_bank-logo',
                        '_account',
                        '_service_code-value',
                        '_point_of_initiation_method',
                        '_country_code',
                        '_transaction_amount',
                        '_transaction_id',
                        '_transaction_currency',
                        '_message',
                    ]

                    inputIds.forEach((key) => {
                        let inputId = id + key
                        let input = document.getElementById(inputId)
                        if (undefined === input || null === input) {
                            input = document.createElement('input')
                            input.id = inputId
                            input.type = 'hidden'
                            resultContainer.appendChild(input)
                        }
                        let name = key.substring(1)
                        try {
                            if (name.indexOf('-') > 0) {
                                name = name.split('-')
                                let tmpValue = response.data[name[0]]
                                if (tmpValue) {
                                    input.value = tmpValue[name[1]]
                                }
                            } else {
                                input.value = response.data[name]
                            }
                        } catch (ex) {
                            console.log(ex)
                            input.value = ex.message
                        }
                        if (input.value && key === '_bank-logo') {
                            console.log(inputId + "_img")
                            let img = document.getElementById(inputId + "_img")
                            if (img) {
                                img.src = input.value
                            }
                        }
                    })
                    console.log(response.data)
                } else {
                    resultContainer.innerHTML = response.data
                }
            }
        }
        xhr.open("POST", `${location.origin}/api/vietqr_detech`, true)
        xhr.send(formData)
    }
}
