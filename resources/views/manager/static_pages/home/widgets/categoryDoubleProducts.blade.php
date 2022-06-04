<div class="category_double_products_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Select category to show products(2)</h4>
    
    <div class="categories_with_label">
        @if(count($categories))
        @foreach($categories as $category)
        <label><input {{ in_array($category->id,$home_data['categoryDoubleProducts'])?'checked':'' }} type="checkbox" value="{{ $category->id }}" name="categoryDoubleProducts[]" /> {{ $category->meta->title }}</label>&nbsp;
        @endforeach
        @else 
        <label>No category found</label>
        @endif
    </div>
</div>