<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js" defer></script>
<script>
    // Initialize SortableJS
    document.addEventListener("DOMContentLoaded", function () {
        new Sortable(document.getElementById('sortable-list'), {
            animation: 150,
            ghostClass: 'ghost',
            onEnd: function (evt) {
                const items = Array.from(evt.to.children).map(item => ({
                    id: item.getAttribute('task-id'),
                    text: item.textContent.trim()
                }));

                Livewire.dispatch('updatedPriority', {tasks: items})
            }
        });
    });
</script>
