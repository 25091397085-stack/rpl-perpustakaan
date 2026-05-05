window.addEventListener("DOMContentLoaded", (event) => {
    const datatablesSimple = document.getElementById("datatablesSimple");

    if (datatablesSimple) {
        new simpleDatatables.DataTable(datatablesSimple, {
            perPageSelect: false,
            labels: {
                info: "", // ⬅️ ini yang menghilangkan teks
            },
        });

        const dataTableTop = datatablesSimple
            .closest(".datatable-wrapper")
            ?.querySelector(".datatable-top");
        const searchInput = dataTableTop?.querySelector('input[type="search"]');

        if (dataTableTop && searchInput) {
            const searchWrapper = searchInput.closest("div");
            if (searchWrapper) {
                dataTableTop.prepend(searchWrapper);
            }
        }
    }
});
