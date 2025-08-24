@extends('admin.dashboard')

@section('title', 'Tạo kịch bản mới')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Tạo kịch bản mới</h3>
                        <a href="{{ route('admin.chat.templates') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.chat.templates.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    {{-- Keyword --}}
                                    <div class="form-group">
                                        <label for="keyword">Từ khóa <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('keyword') is-invalid @enderror"
                                            id="keyword" name="keyword" value="{{ old('keyword') }}"
                                            placeholder="Ví dụ: chào hỏi, giá cả, dịch vụ...">
                                        @error('keyword')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    {{-- Response --}}
                                    <div class="form-group">
                                        <label for="response">Nội dung phản hồi <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('response') is-invalid @enderror" id="response" name="response" rows="6"
                                            placeholder="Nhập nội dung phản hồi tự động...">{{ old('response') }}</textarea>
                                        @error('response')
                                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    {{-- Suggested services --}}
                                    <div class="form-group position-relative">
                                        <label for="suggested_services">Dịch vụ gợi ý</label>
                                        <input type="text" class="form-control" id="suggested_services"
                                            placeholder="Nhập dịch vụ...">
                                        <div id="suggested_services_list" class="list-group mt-1"></div>
                                        <div id="selected-services" class="mt-2"></div>
                                    </div>
                                </div>

                                {{-- Priority --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="priority">Độ ưu tiên</label>
                                        <select class="form-control @error('priority') is-invalid @enderror" id="priority"
                                            name="priority">
                                            <option value="0" {{ old('priority') == '0' ? 'selected' : '' }}>Thấp (0)
                                            </option>
                                            <option value="1" {{ old('priority') == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('priority') == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ old('priority') == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ old('priority') == '4' ? 'selected' : '' }}>4
                                            </option>
                                            <option value="5" {{ old('priority') == '5' ? 'selected' : '' }}>Trung
                                                bình (5)</option>
                                            <option value="6" {{ old('priority') == '6' ? 'selected' : '' }}>6
                                            </option>
                                            <option value="7" {{ old('priority') == '7' ? 'selected' : '' }}>7
                                            </option>
                                            <option value="8" {{ old('priority') == '8' ? 'selected' : '' }}>8
                                            </option>
                                            <option value="9" {{ old('priority') == '9' ? 'selected' : '' }}>9
                                            </option>
                                            <option value="10" {{ old('priority') == '10' ? 'selected' : '' }}>Cao nhất
                                                (10)</option>
                                        </select>
                                        @error('priority')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Template với độ ưu tiên cao hơn sẽ được ưu tiên sử dụng
                                        </small>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Hướng dẫn</h5>
                                        </div>
                                        <div class="card-body">
                                            <ul class="mb-0">
                                                <li><strong>Từ khóa:</strong> Tìm kiếm không phân biệt hoa thường</li>
                                                <li><strong>Độ ưu tiên:</strong> 0 (thấp) đến 10 (cao)</li>
                                                <li><strong>Dịch vụ:</strong> Để trống nếu không cần</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <hr>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tạo 
                            </button>
                            <a href="{{ route('admin.chat.templates') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times"></i> Hủy bỏ
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const input = document.getElementById('suggested_services');
        const list = document.getElementById('suggested_services_list');
        const selectedContainer = document.getElementById('selected-services');
        const form = document.querySelector('form');

        let debounceTimer;

        function debounce(func, delay) {
            return function(...args) {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(this, args), delay);
            };
        }

        function fetchServices(query) {
            if (!query) {
                list.innerHTML = '';
                return;
            }

            fetch(`{{ route('admin.chat.services.search') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    list.innerHTML = '';
                    if (data.length === 0) {
                        list.innerHTML = '<div class="list-group-item text-muted">Không tìm thấy dịch vụ</div>';
                        return;
                    }
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'list-group-item list-group-item-action';
                        div.textContent = item.name;
                        div.onclick = () => {
                            addService(item.id, item.name);
                            input.value = '';
                            list.innerHTML = '';
                        };
                        list.appendChild(div);
                    });
                });
        }

        function addService(id, name) {
            if (document.querySelector(`input[name="suggested_services[]"][value="${id}"]`)) return;

            const badge = document.createElement('span');
            badge.className = 'badge badge-primary mr-2 mb-2';
            badge.textContent = name + ' ';

            const removeBtn = document.createElement('span');
            removeBtn.textContent = '×';
            removeBtn.style.cursor = 'pointer';

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'suggested_services[]';
            hiddenInput.value = id; // ✅ quan trọng: chỉ là id số, không JSON.stringify

            removeBtn.onclick = () => {
                badge.remove();
                hiddenInput.remove();
            };

            badge.appendChild(removeBtn);
            selectedContainer.appendChild(badge);
            selectedContainer.appendChild(hiddenInput);
        }

        input.addEventListener('input', debounce(function() {
            fetchServices(this.value.trim());
        }, 300));

        document.addEventListener('click', e => {
            if (!input.contains(e.target) && !list.contains(e.target)) {
                list.innerHTML = '';
            }
        });

        // Load lại dịch vụ đã chọn khi validate fail
        @if (old('suggested_services'))
            const oldServices = @json(old('suggested_services'));
            oldServices.forEach(id => {
                fetch(`/admin/services/${id}`)
                    .then(res => res.json())
                    .then(service => {
                        if (service && service.id) addService(service.id, service.name);
                    });
            });
        @endif
    </script>
@endpush
