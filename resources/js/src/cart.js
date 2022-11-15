const cartAdd = () => {
    document.querySelectorAll('.js-cart-add').forEach(item => {
        item.addEventListener('click', e => {
            e.preventDefault()

            const url = e.target.href
            const parent = e.target.closest('tr')
            const price = parent.querySelector('.js-cart-item-price').value  // parent.querySelector('.js-cart-item-dealer-price').textContent
            const quantity = parent.querySelector('.js-cart-item-quantity').value
            const cartCount = document.querySelector('.cart-product-count')

            axios({
                url,
                method: 'post',
                data: {
                    price,
                    quantity
                }
            }).then(function (response) {
                cartCount.textContent = response.data.count
                $.jGrowl(response.data.text, {
                    position: 'bottom-right'
                })
            }).catch(function (error) {
                console.log(error)
            })
        })
    })
}

cartAdd()

const cartUpdate = () => {
    document.querySelectorAll('.js-cart-update-qty').forEach(item => {
        item.addEventListener('change', e => {
            const url = '/cart/update'
            const parent = e.target.closest('.item')
            const total = document.querySelector('.js-cart-total')
            const rowId = parent.querySelector('.js-cart-rowId').value
            const quantity = e.target.value

            axios({
                url,
                method: 'post',
                data: {
                    rowId,
                    quantity
                }
            }).then(function (response) {
                total.textContent = response.data.total

                $.jGrowl(response.data.text, {
                    position: 'bottom-right'
                })
            }).catch(function (error) {
                console.log(error)
            })
        })
    })
}

cartUpdate()

// const cartRemove = () => {
//     const cartRemoveBtn = document.querySelectorAll('.js-cart-remove')
//
//     if (cartRemoveBtn === null) return
//
//     cartRemoveBtn.forEach(item => {
//         item.addEventListener('click', e => {
//             e.preventDefault()
//
//             const url = e.target.href
//
//             axios({
//                 url,
//                 method: 'post'
//             }).then(response => {
//                 $.jGrowl(response.data, {
//                     position: 'bottom-right'
//                 })
//             }).catch(error => {
//                 console.log(error)
//             }).then(() => cartShow())
//         })
//     })
// }

// const cartClear = () => {
//     const cartClearBtn = document.querySelector('.js-cart-clear')
//
//     if (cartClearBtn === null) return
//
//     cartClearBtn.addEventListener('click', e => {
//         e.preventDefault()
//
//         const url = e.target.href
//
//         axios({
//             url,
//             method: 'post'
//         }).then(response => {
//             $.jGrowl(response.data, {
//                 position: 'bottom-right'
//             })
//         }).catch(error => {
//             console.log(error)
//         }).then(() => cartShow())
//     })
// }

// const cartShow = () => {
//     const cartPanel = document.getElementById('basket-panel');
//
//     if (cartPanel === null) return
//
//     const url = '/cart'
//
//     axios({
//         url,
//         method: 'get'
//     }).then(response => {
//         cartPanel.innerHTML = response.data
//     }).catch(error => {
//         console.log(error)
//     }).then(() => {
//         // cartRemove()
//         // cartClear()
//         cartUpdate()
//     })
// }

//cartShow()
