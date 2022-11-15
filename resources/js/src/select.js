const cart = require('./cart')

$("#warehouse-select").select2()

$("#brand-select").select2()

$('.js-select').on('select2:select', function (e) {
    const items = getItems()

    updateProducts(items)
});

document.querySelectorAll('.js-sort-link').forEach(item => {
    item.addEventListener('click', e => {
        e.preventDefault()

        updateSortItem(item)

        const items = getItems()

        updateProducts(items)
    })
})

const updateSortItem = item => {
    if (item.classList.contains('active')) {
        item.dataset.dir = item.dataset.dir == 'asc' ? 'desc' : 'asc'
        item.dataset.dir == 'desc' ? item.classList.add('desc') : item.classList.remove('desc')
    } else {
        document.querySelectorAll('.js-sort-link').forEach(item => {
            item.classList.remove('active')
            item.classList.remove('desc')
            item.dataset.dir = 'asc'
        })

        item.classList.add('active')
    }
}

const updateProducts = items => {
    const tableBody = document.querySelector('.js-update-filter')
    const url = '/catalog/update'

    axios({
        url,
        method: 'post',
        data: {
            ...items
        }
    }).then(response => {
        tableBody.innerHTML = response.data
    }).catch(error => {
        console.log(error)
    }).then(() => {
        cart.cartAdd()
    })
}

const getItems = () => {
    const selectBrand = document.getElementById('brand-select')
    const selectWarehouse = document.getElementById('warehouse-select')
    const orderItem = document.querySelector('.js-sort-link.active')

    const brand_id = selectBrand ? selectBrand.options[selectBrand.selectedIndex].value : 0
    const warehouse_id = selectWarehouse ? selectWarehouse.options[selectWarehouse.selectedIndex].value : 0
    const sort_by = orderItem.dataset.sort
    const sort_dir = orderItem.dataset.dir

    return { brand_id, warehouse_id, sort_by, sort_dir }
}
