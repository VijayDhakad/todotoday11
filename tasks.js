
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = this.parentElement.getAttribute('data-id');

            fetch(`get_task.php?id=${taskId}`, { method: 'GET' })
                .then(response => response.json())
                .then(task => {
                    document.getElementById('task-id').value = task.id;
                    document.getElementById('edit-title').value = task.title;
                    document.getElementById('edit-description').value = task.description;
                    document.getElementById('edit-category').value = task.category;
                    document.getElementById('edit-status').value = task.status;
                    document.getElementById('edit-due-date').value = task.due_date;

                    document.getElementById('edit-task-modal').style.display = 'block';
                });
        });
    });




    const editForm = document.getElementById('edit-task-form');
    editForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(editForm);

        fetch('edit_task.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'success') {
                alert('Task updated');
            
            } else {
                alert('Error updating tasks');
            }
        });
    });



const cancelbtn=document.getElementById("close-modal")
 
cancelbtn.onclick=function(){

 document.getElementById("edit-task-modal").style.display="none";

}
    





// document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const taskId = this.getAttribute('data-id');
            const newStatus = encodeURIComponent(this.value);  // Ensure the status is URL-encoded

            fetch('update_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${encodeURIComponent(taskId)}&status=${newStatus}`  // URL-encode parameters
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'success') {  // Trim to remove extra spaces/newlines
                    alert('Status updated successfully');
                    location.reload();
                } else {
                    alert('Error updating status');
                }
            })
            .catch(error => {
                console.error('Error:', error);  // Log network or other errors
                alert('Error updating status');
            });
        });
    });
});











// for delete task 
function deleteTask(taskId) {
    if (confirm("Are you sure you want to delete this task?")) {
        // Send an AJAX request to delete_task.php
        fetch(`delete_task.php?id=${taskId}`, {
            method: 'GET'
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'success') {
             
                alert('Task deleted successfully!');
                
            } else {
                
                
                alert('Failed to delete the task.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while trying to delete the task.');
        });
    }
}


