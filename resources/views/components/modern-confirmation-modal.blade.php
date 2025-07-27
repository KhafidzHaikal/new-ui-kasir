@props([
    'id' => 'confirmationModal',
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin?',
    'confirmText' => 'Ya, Lanjutkan',
    'cancelText' => 'Batal',
    'confirmClass' => 'btn-danger',
    'icon' => 'warning'
])

<!-- Modern Confirmation Modal -->
<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modern-confirmation-modal">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="close modern-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body text-center py-4">
                <!-- Icon Section -->
                <div class="confirmation-icon mb-3">
                    @if($icon === 'warning')
                        <div class="icon-circle warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    @elseif($icon === 'danger')
                        <div class="icon-circle danger">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                    @elseif($icon === 'info')
                        <div class="icon-circle info">
                            <i class="fas fa-info-circle"></i>
                        </div>
                    @else
                        <div class="icon-circle question">
                            <i class="fas fa-question-circle"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Title -->
                <h4 class="confirmation-title mb-3">{{ $title }}</h4>
                
                <!-- Message -->
                <p class="confirmation-message text-muted mb-4">{{ $message }}</p>
            </div>
            
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-secondary btn-modern mr-2" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> {{ $cancelText }}
                </button>
                <button type="button" class="btn {{ $confirmClass }} btn-modern" id="{{ $id }}Confirm">
                    <i class="fas fa-check mr-1"></i> {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.modern-confirmation-modal {
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    border: none;
}

.modern-confirmation-modal .modal-header {
    padding: 15px 20px 0;
}

.modern-close {
    font-size: 24px;
    font-weight: 300;
    color: #999;
    opacity: 0.7;
    transition: all 0.3s ease;
}

.modern-close:hover {
    opacity: 1;
    color: #666;
}

.confirmation-icon {
    display: flex;
    justify-content: center;
    align-items: center;
}

.icon-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: white;
    animation: pulse 2s infinite;
}

.icon-circle.warning {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

.icon-circle.danger {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.icon-circle.info {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.icon-circle.question {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.4);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(52, 152, 219, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(52, 152, 219, 0);
    }
}

.confirmation-title {
    font-weight: 600;
    color: #2c3e50;
    font-size: 1.4rem;
}

.confirmation-message {
    font-size: 1rem;
    line-height: 1.5;
}

.btn-modern {
    border-radius: 25px;
    padding: 10px 25px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    min-width: 120px;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.btn-danger.btn-modern {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.btn-danger.btn-modern:hover {
    background: linear-gradient(135deg, #c0392b, #a93226);
}

.btn-secondary.btn-modern {
    background: linear-gradient(135deg, #95a5a6, #7f8c8d);
}

.btn-secondary.btn-modern:hover {
    background: linear-gradient(135deg, #7f8c8d, #6c7b7d);
}
</style>
