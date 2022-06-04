@extends('manager.common.layout')
@section('content')
<div class="pages">
    <form action="{{ url('/manager/pages/save') }}" class="">
        @csrf 
        
        @include('manager.common.sessionMessage')

        <div class="bg-gray admin_info_box">
            <div class="bg-info text-white admin_info_box_header">
                <h2><b>Page</b></h2>
                <div class="title_right">
                    <div class="btn-group" role="group" aria-label="Switch language">
                        <button type="button" class="btn btn-dark" value="he">Hebrew</button>
                    </div>
                </div>
            </div>
            <div class="admin_info_box_body">
                <div class="page_form">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group" style="display: block;"><label class="mb-0">Title<span class="ml-2 small"><span class="small badge badge-primary">Hebrew</span></span></label><input class="form-control" type="text" name="he_title" data-group="" data-lang="" placeholder="Page title in hebrew" required="" min="" value=""></div>
                            <div class="form-group">
                                <p class="small text-dark"><b>https://sharentag.com/page/<u class="text-danger"></u></b></p>
                            </div>
                            <div class="form-group" style="display: block;"><label class="mb-0">Sub title<span class="ml-2 small"><span class="small badge badge-primary">Hebrew</span></span></label><input class="form-control" type="text" name="he_subTitle" data-group="" data-lang="" placeholder="Page subtitle in hebrew" min="" value=""></div>
                            <div class="form-group">
                                <label class="mb-0">Status</label>
                                <div>
                                <select data-group="" name="status" class="form-control" required="">
                                    <option value="1">Active</option>
                                    <option value="0">Draft</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-group" style="display: block;"><label class="mb-0">Content<span class="ml-2 small"><span class="small badge badge-primary">Hebrew</span></span></label><textarea name="he_content" placeholder="Page content in Hebrew" class="form-control" rows="6" data-group="" data-lang=""></textarea></div>
                        </div>
                        <div class="col-md-4">
                            <div class="file_uploader">
                                <div class="uploading_file_container"><img src="" style="display: none;"><input name="fileInputer" type="file" accept="image/*"><button name="uploaderButton" class="btn btn-dark" type="button">Upload Image</button></div>
                                <div class="progress" style="display: none;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 10%;"></div>
                                </div>
                            </div>
                            <p class="small text-danger"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray admin_info_box">
            <div class="bg-info text-white admin_info_box_header">
                <h2><b>SEO</b></h2>
                <div class="title_right">
                    <div class="btn-group" role="group" aria-label="Switch language">
                        <button type="button" class="btn btn-dark" value="he">Hebrew</button>
                    </div>
                </div>
            </div>
            <div class="admin_info_box_body">
                <div class="category_seo_form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" style="display: block;">
                            <label class="mb-0">Meta title<span class="ml-2 small"><span class="small badge badge-primary">Hebrew</span></span></label>
                            <input class="form-control" type="text" name="he_metaTitle" placeholder="Meta title in hebrew" min="" value="">
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" style="display: block;">
                            <label class="mb-0">Meta keywords<span class="ml-2 small"><span class="small badge badge-primary">Hebrew</span></span></label>
                            <input class="form-control" type="text" name="he_metaKeywords" placeholder="Meta keywords in hebrew" min="" value=""></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" style="display: block;">
                            <label class="mb-0">Meta description<span class="ml-2 small"><span class="small badge badge-primary">Hebrew</span></span></label>
                            <textarea name="he_metaDescription" placeholder="Meta description in Hebrew" class="form-control" rows="6" data-group="" data-lang=""></textarea></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection