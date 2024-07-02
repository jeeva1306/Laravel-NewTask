<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Task</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        <div class="form-group mt-3" id="subcategory-dropdown" style="display: none;">
            <label for="subcategory_dropdown">Subcategory</label>
            <select name="subcategory_dropdown" id="subcategory_dropdown" class="form-control">
                <option value="">Subcategory List</option>
            </select>
        </div>

        <h2 class="mt-5">Categories and SubCategories List</h2>
        <ul class="list-group">
            @foreach ($parentCategories as $category)
                <li class="list-group-item"><strong>{{ $category->name }}</strong></li>
                @if ($category->child->isNotEmpty())
                    <ul class="list-group ml-4 mt-2">
                        @foreach ($category->child as $child)
                            <li class="list-group-item" style="font-style: italic; color: red;">>> {{ $child->name }}</li>
                            @if ($child->child->isNotEmpty())
                                <ul class="list-group ml-4 mt-2">
                                    @foreach ($child->child as $subchild)
                                        <li class="list-group-item">2. {{ $subchild->name }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        @endforeach
                    </ul>
                @endif
            @endforeach
        </ul>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        $(document).ready(function () {
            const categoryDropdown = $('#category_dropdown');
            const subcategoryDropdown = $('#subcategory-dropdown');
            const categoryInput = $('#category-input');
            const subcategoryInput = $('#subcategory-input');
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            categoryDropdown.on('change', function () {
                const selectedCategory = categoryDropdown.val();
                if (selectedCategory) {
                    categoryInput.hide();
                    subcategoryInput.show();
                    fetchSubcategory(selectedCategory);
                } else {
                    categoryInput.show();
                    subcategoryInput.hide();
                    subcategoryDropdown.hide();
                }
            });

            function fetchSubcategory(parentId) {
                // console.log(parentId);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: `/categories/${parentId}/child`,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        const subcategorySelect = $('#subcategory_dropdown');
                        subcategorySelect.html('<option value="">Subcategory List</option>');
                        if (data.length > 0) {
                            data.forEach(subcategory => {
                                const option = $('<option></option>').val(subcategory.id).text(subcategory.name);
                                subcategorySelect.append(option);
                            });
                            subcategoryDropdown.show();
                        } else {
                            subcategorySelect.hide();
                        }
                    }
                });
            }

            $('#add-category').on('click', function () {
                const name = $('#category_name').val();
                $.ajax({
                    url: '/categories',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ name }),
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (data) {
                        if (data.success) {
                            location.reload();
                        }
                    }
                });
            });

            $('#add-subcategory').on('click', function () {
                const name = $('#subcategory_name').val();
                const parentId = categoryDropdown.val();
                $.ajax({
                    url: '/subcategories',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ name, parent: parentId }),
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (data) {
                        if (data.success) {
                            location.reload();
                        }
                    }
                })
            })
        })
    </script>

    <!-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryDropdown = document.getElementById('category_dropdown');
            const subcategoryDropdown = document.getElementById('subcategory-dropdown');
            const categoryInput = document.getElementById('category-input');
            const subcategoryInput = document.getElementById('subcategory-input');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            categoryDropdown.addEventListener('change', function () {
                const selectedCategory = categoryDropdown.value;
                if (selectedCategory) {
                    categoryInput.style.display = 'none';
                    subcategoryInput.style.display = 'block';
                    fetchSubcategory(selectedCategory);
                } else {
                    categoryInput.style.display = 'block';
                    subcategoryInput.style.display = 'none';
                }
            });

            function fetchSubcategory(parentId) {
                // console.log(parentId);
                var xmlhttp;
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }

                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        const subcategorySelect = document.getElementById('subcategory_dropdown');
                        subcategorySelect.innerHTML = '<option value="">Subcategory List</option>';
                        const data = JSON.parse(xmlhttp.responseText);
                        console.log(data);
                        if (data.length > 0) {
                            data.array.foreach(subcategory => {
                                const option = document.createElement('option');
                                option.value = subcategory.id;
                                option.textContent = subcategory.name;
                                subcategorySelect.appendChild(option);
                            });
                            subcategoryDropdown.style.display = 'block';
                        } else {
                            subcategoryDropdown.style.display = 'none';
                        }
                    }
                };
                xmlhttp.open("GET", '/categories/${parentId}/child', true);
                xmlhttp.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xmlhttp.send();
            }

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
    </script> -->
</body>

</html>
