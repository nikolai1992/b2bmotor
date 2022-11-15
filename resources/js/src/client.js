const categoriesAdd = () => {
    document.querySelector('.js-exclude-categories').addEventListener('click', evt => {
        const url = '/clients/update-categories'
        const modal = evt.target.closest('#categoriesModal')
        const categoriesList = modal.querySelector('.categories-list')
        const inputsList = categoriesList.querySelectorAll('input[name="exclude_categories[]"]:checked')
        let excludeCategories = []
        const clientId = modal.dataset.clientId

        inputsList.forEach(i => excludeCategories.push(i.value))

        axios({
            url,
            method: 'post',
            data: {
                excludeCategories,
                clientId
            }
        }).then(function (response) {
            $.jGrowl(response.data, {
                position: 'bottom-right'
            })
        }).catch(function (error) {
            console.log(error)
        })
    })
}

categoriesAdd()
