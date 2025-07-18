/**
 * Custom JavaScript for PR-Notify Application
 */

document.addEventListener('DOMContentLoaded', function() {
    // Enable Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Enable Bootstrap popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    // Activity Type Other Field Toggle
    const actTypeSelect = document.getElementById('act_type_id');
    const actTypeDetailField = document.getElementById('act_type_detail_field');
    
    if (actTypeSelect && actTypeDetailField) {
        function toggleActTypeDetailField() {
            if (actTypeSelect.value === '99') {
                actTypeDetailField.style.display = 'block';
                document.getElementById('act_type_detail').setAttribute('required', 'required');
            } else {
                actTypeDetailField.style.display = 'none';
                document.getElementById('act_type_detail').removeAttribute('required');
            }
        }
        
        // Initial check
        toggleActTypeDetailField();
        
        // On change
        actTypeSelect.addEventListener('change', toggleActTypeDetailField);
    }
    
    // Image Preview on Upload
    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview');
    
    if (imageInput && previewContainer) {
        imageInput.addEventListener('change', function() {
            previewContainer.innerHTML = '';
            
            if (this.files) {
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    
                    if (!file.type.match('image.*')) {
                        continue;
                    }
                    
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 mb-3';
                        
                        const img = document.createElement('img');
                        img.className = 'img-thumbnail';
                        img.src = e.target.result;
                        img.style.height = '150px';
                        img.style.objectFit = 'cover';
                        
                        col.appendChild(img);
                        previewContainer.appendChild(col);
                    }
                    
                    reader.readAsDataURL(file);
                }
            }
        });
    }
    
    // Confirm Delete
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            if (!confirm('คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?')) {
                e.preventDefault();
            }
        });
    });
    
    // Lightbox for Activity Images
    document.querySelectorAll('.activity-image').forEach(image => {
        image.addEventListener('click', function() {
            const src = this.getAttribute('src');
            const alt = this.getAttribute('alt') || 'Activity Image';
            
            const modal = document.createElement('div');
            modal.classList.add('modal', 'fade');
            modal.setAttribute('tabindex', '-1');
            modal.innerHTML = `
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${alt}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="${src}" class="img-fluid" alt="${alt}">
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            modal.addEventListener('hidden.bs.modal', function() {
                document.body.removeChild(modal);
            });
        });
    });
});