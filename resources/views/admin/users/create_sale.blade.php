@extends('layouts.admin')


@section('page-action-back-button')
    <div class="d-flex justify-content-between align-items-center">
        @include('components.back-btn', [
            'route' => route('sale.index'),
            'title' => 'Quay về trang quản lý sale'
        ])
    </div>
@endsection


@section('content')
<div class="card">
    <div class="card-body">
        @include('admin.users.add-form', [
            'extraFields' => 'admin.users.sale-feilds-create',
        ])
    </div>
</div>
@endsection

@section('js')
    <script>
        function previewSelectedImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('previewImage');
            const placeholder = document.getElementById('placeholderText');
            const removeBtn = document.querySelector('.remove-image');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                    removeBtn.style.display = 'flex';
                };
                reader.readAsDataURL(file);
            }
        }

        function removeImage(event) {
            event.stopPropagation(); // Không mở lại file picker khi bấm nút close
            const input = document.getElementById('image');
            const preview = document.getElementById('previewImage');
            const placeholder = document.getElementById('placeholderText');
            const removeBtn = document.querySelector('.remove-image');

            input.value = '';
            preview.src = '#';
            preview.style.display = 'none';
            placeholder.style.display = 'block';
            removeBtn.style.display = 'none';
        }

        // Ẩn nút close lúc đầu
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.remove-image').style.display = 'none';
        });
    </script>
@endsection
