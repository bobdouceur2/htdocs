function sortTable(column) {
    const url = new URL(window.location.href);
    const currentSort = url.searchParams.get('sort');
    let newSort;

    if (currentSort === column + 'Asc') {
        newSort = column + 'Desc';
    } else {
        newSort = column + 'Asc';
    }

    url.searchParams.set('sort', newSort);
    window.location.href = url.toString();
}

function togglePopup() {
    const popup = document.getElementById('settingsPopup');
    const overlay = document.getElementById('overlay');
    const isVisible = popup.style.display === 'block';

    popup.style.display = isVisible ? 'none' : 'block';
    overlay.style.display = isVisible ? 'none' : 'block';
}

function toggleColumnVisibility() {
    const checkboxes = document.querySelectorAll('.column-toggle input[type="checkbox"]');
    checkboxes.forEach((checkbox) => {
        const columnIndex = checkbox.parentElement.getAttribute('data-column-index');
        const isVisible = checkbox.checked;

        document.querySelectorAll(`table tr`).forEach(row => {
            if (row.cells[columnIndex]) {
                row.cells[columnIndex].style.display = isVisible ? '' : 'none';
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.column-toggle input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', toggleColumnVisibility);
    });
    toggleColumnVisibility(); // Initial call to set visibility based on initial checkbox states
});

function sortTableByColumn(columnIndex, ascending = true) {
    const tableContainer = document.querySelector("table tbody");
    const rows = Array.from(tableContainer.getElementsByTagName("tr"));

    rows.sort((a, b) => {
        const cellA = a.cells[columnIndex];
        const cellB = b.cells[columnIndex];

        if (!cellA || !cellB) return 0; // Ensure the cells exist

        const textA = cellA.innerText.toLowerCase();
        const textB = cellB.innerText.toLowerCase();

        if (textA < textB) return ascending ? -1 : 1;
        if (textA > textB) return ascending ? 1 : -1;
        return 0;
    });

    // Append sorted rows back to the table
    rows.forEach(row => tableContainer.appendChild(row));
}

function attachSortingHandlers() {
    document.querySelectorAll('th a').forEach(function(anchor, index) {
        anchor.addEventListener('click', function(event) {
            event.preventDefault();
            const currentSort = anchor.querySelector('.sort-icon').classList.contains('sort-asc');
            sortTableByColumn(index, !currentSort);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachSortingHandlers);

document.addEventListener('DOMContentLoaded', function() {
    const draggables = document.querySelectorAll('.draggable');
    const container = document.getElementById('columnList');

    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', () => {
            draggable.classList.add('dragging');
        });

        draggable.addEventListener('dragend', () => {
            draggable.classList.remove('dragging');
            updateTableColumnsOrder(); // Mettre à jour l'ordre des colonnes après le glisser-déposer
        });
    });

    container.addEventListener('dragover', e => {
        e.preventDefault();
        const afterElement = getDragAfterElement(container, e.clientY);
        const draggable = document.querySelector('.dragging');
        if (afterElement == null) {
            container.appendChild(draggable);
        } else {
            container.insertBefore(draggable, afterElement);
        }
    });

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.draggable:not(.dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    // Initial call to set visibility based on initial checkbox states
    toggleColumnVisibility();
    loadColumnOrder();
});

function updateTableColumnsOrder() {
    const columnOrder = Array.from(document.querySelectorAll('.column-toggle .draggable'))
        .map(draggable => draggable.getAttribute('data-column-index'));

    const table = document.querySelector('table');
    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {
        const cells = Array.from(row.children);
        const sortedCells = columnOrder.map(index => cells[index]);
        sortedCells.forEach(cell => row.appendChild(cell));
    });

    saveColumnOrder();
}

function saveColumnOrder() {
    const orderedColumns = Array.from(document.querySelectorAll('.column-toggle .draggable'))
        .map(draggable => draggable.getAttribute('data-column-index'));
    localStorage.setItem('columnOrder', JSON.stringify(orderedColumns));
}

function loadColumnOrder() {
    const savedOrder = JSON.parse(localStorage.getItem('columnOrder'));
    if (savedOrder) {
        const container = document.getElementById('columnList');
        savedOrder.forEach(columnIndex => {
            const draggable = container.querySelector(`[data-column-index="${columnIndex}"]`);
            container.appendChild(draggable);
        });
        updateTableColumnsOrder(); // Appliquer l'ordre initialement
    }
}

document.addEventListener('DOMContentLoaded', function() {
    loadColumnOrder();
    attachSortingHandlers();
});
