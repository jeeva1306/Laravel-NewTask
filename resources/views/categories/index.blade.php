<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Task</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container mt-5">
        <h1>Category Task</h1>

        <form id="category-form">
            @csrf
            <div class="form-group">
                <label for="category_dropdown">Category</label>
                <select name="category_dropdown" id="category_dropdown" class="form-control">
                    <option value="">Category List</option>
                    @foreach ($parentCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mt-3" id="category-input">
                <label for="category_name">Category Name</label>
                <input type="text" name="name" id="category_name" class="form-control">
                <button type="button" class="btn btn-primary mt-3" id="add-category">Add Category</button>
            </div>
        </form>

        <div class="form-group mt-3" id="subcategory-input" style="display: none;">
            <label for="subcategory_name">Subcategory Name</label>
            <input type="text" name="subcategory_name" id="subcategory_name" class="form-control">
            <button type="button" class="btn btn-primary mt-3" id="add-subcategory">Add Subcategory</button>
        </div>

        <h2 class="mt-5">Categories and SubCategories List</h2>
        <ul class="list-group">
            @foreach ($parentCategories as $category)
                <li class="list-group-item"><strong>{{ $category->name }}</strong></li>
                @if ($category->child->isNotEmpty())
                    <ul class="list-group ml-4 mt-2">
                        @foreach ($category->child as $child)
                            <li class="list-group-item" style="font-style: italic; color: red;">>> {{ $child->name }}</li>
                        @endforeach
                    </ul>
                @endif
            @endforeach
        </ul>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryDropdown = document.getElementById('category_dropdown');
            const categoryInput = document.getElementById('category-input');
            const subcategoryInput = document.getElementById('subcategory-input');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            categoryDropdown.addEventListener('change', function () {
                const selectedCategory = categoryDropdown.value;
                if (selectedCategory) {
                    categoryInput.style.display = 'none';
                    subcategoryInput.style.display = 'block';
                } else {
                    categoryInput.style.display = 'block';
                    subcategoryInput.style.display = 'none';
                }
            });

            document.getElementById('add-category').addEventListener('click', function () {
                const name = document.getElementById('category_name').value;
                fetch('/categories', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ name })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
            });

            document.getElementById('add-subcategory').addEventListener('click', function () {
                const name = document.getElementById('subcategory_name').value;
                const parentId = categoryDropdown.value;
                fetch('/subcategories', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ name, parent: parentId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
            });
        });
    </script>
</body>

</html>
