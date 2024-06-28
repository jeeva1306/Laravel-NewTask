<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category List</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Category List</h1>

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="parent_id">Parent Category</label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">Null</option>
                    @foreach ($parentCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="name">Category Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add Category</button>
        </form>

        <form action="{{ route('subcategories.store') }}" method="POST">
            @csrf
            <div class="form-group mt-4">
                <label for="parent_category">Select Parent Category</label>
                <select name="parent_id" id="parent_category" class="form-control" required>
                    <option value="">Select Parent Category</option>
                    @foreach ($parentCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mt-3">
                <label for="subcategory_name">Subcategory Name</label>
                <input type="text" name="name" id="subcategory_name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add Subcategory</button>
        </form>

        <h2 class="mt-5">Categories</h2>
        <ul class="list-group">
            @foreach ($parentCategories as $category)
                <li class="list-group-item" style="background-color: green;">{{ $category->name }}</li>
                @if ($category->child->isNotEmpty())
                    <ul class="list-group ml-4">
                        @foreach ($category->child as $child)
                            <li class="list-group-item" style="background-color: red;">{{ $child->name }}</li>
                            @if ($child->child->isNotEmpty())
                                <ul class="list-group ml-4">
                                    @foreach ($child->child as $subchild)
                                        <li class="list-group-item">{{ $subchild->name }}</li>
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
</body>

</html>
