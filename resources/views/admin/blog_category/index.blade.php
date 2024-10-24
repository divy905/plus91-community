@extends('admin.layouts.layouts')
<?php
$BackUrl = CustomHelper::BackUrl();
$routeName = CustomHelper::getAdminRouteName();
$storage = Storage::disk('public');
$path = 'blog_category/';
?>
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">

    <div class="page-header">
      <div class="row">
        <div class="col-sm-12">
          <div class="page-sub-header">
            <h3 class="page-title">Blog Category</h3>
            <ul class="breadcrumb">
              <!-- <li class="breadcrumb-item"><a href="students.html">Student</a></li> -->
              <li class="breadcrumb-item active">All Blog Category</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <form action="" method="post">
      @csrf
      <div class="student-group-form">
        <div class="row">
          <div class="col-lg-4">
            <div class="form-group">
              <label for="userName"> Category</label>
              <select name="category_id" class="form-control" onchange="fill_value(this.value)">
                <option value="" selected>Select Category</option>
                <?php if(!empty($categories)){
                  foreach($categories as $cat){
                    ?>
                    <option value="{{$cat->id}}" class='select_continent' catvalue='{{$cat->name}}'>{{$cat->name??''}}</option>
                  <?php }}?>
                </select>
              </div>
            </div>
            <div class="col-12 col-sm-4">
              <div class="form-group">
                <label for="userName"> Category Name</label>
                <input type="text" name="category_name" value="{{ old('category_name') }}" id="category_name" class="form-control"  maxlength="255" placeholder="Enter Category Name" />

              </div>
            </div>

            <div class="col-12 col-sm-4">
              <div class="form-group">
                <label for="userName"> Keywords</label>
                <input type="text" name="keywords" value="{{ old('keywords') }}" id="keywords" class="form-control"  maxlength="255" placeholder="Enter keywords" />

              </div>
            </div>
            <div class="col-12 col-sm-4">
              <div class="form-group">
                <label for="userName">Robots</label>
                <select name="robots" class="form-control">
                  <option value="index" <?php if(old('robots') == 'index') echo "selected";?>>Index</option>
                  <option value="follow" <?php if(old('robots') == 'follow') echo "selected";?>>Follow</option>
                  <option value="index, follow" <?php if(old('robots') == 'index, follow') echo "selected";?>>Both</option>
                  <option value="noindex, nofollow" <?php if(old('robots') == 'noindex, nofollow') echo "selected";?>>No Index , No Follow</option>
                </select>

              </div>
            </div>

            <div class="col-12 col-sm-4">
              <div class="form-group">
                <label for="userName"> Canonical</label>
                <input type="text" name="canonical" value="{{ old('canonical') }}" id="canonical" class="form-control"  maxlength="255" placeholder="Enter canonical" />

              </div>
            </div>


            <div class="col-12 col-sm-4">
              <div class="form-group">
                <label for="userName"> OG Title</label>
                <input type="text" name=" og_title" value="{{ old(' og_title') }}" id=" og_title" class="form-control"  maxlength="255" placeholder="Enter  og_title" />

              </div>
            </div>

            <div class="col-12 col-sm-4">
              <div class="form-group">
                <label for="userName"> OG Description</label>
                <input type="text" name="og_description" value="{{ old('og_description') }}" id="og_description" class="form-control"  maxlength="255" placeholder="Enter og_description" />

              </div>
            </div>


            <div class="col-12 col-sm-12">
              <div class="form-group">
                <label for="userName">Meta Title</label>
                <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title') }}" onkeyup="get_metatitle_length(this.value)">
                 <span id="count_metatitle">0</span> Characters
              </div>
            </div>

            <div class="col-12 col-sm-12">
              <div class="form-group">
                <label for="userName">Meta Description</label>
                <textarea name="meta_description" id="meta_description" class="form-control" placeholder="Meta Description" onkeyup="get_metadescription_length(this.value)">{{ old('meta_description') }}</textarea>
                 <span id="count_metadescription">0</span> Characters
              </div>
            </div>


            <div class="col-lg-2">
              <div class="search-student-btn">
                <button type="submit" class="btn btn-primary">Add</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <div class="row">
        <div class="col-sm-12">
          <div class="card card-table comman-shadow">
            <div class="card-body">

              <div class="page-header">
                <div class="row align-items-center">
                  <div class="col">
                    <h3 class="page-title">Blog Category</h3>
                  </div>
                  <div class="col-auto text-end float-end ms-auto download-grp">
                    <!-- <a href="{{ route($routeName.'.blogs.add', ['back_url' => $BackUrl]) }}" class="btn btn-primary"><i class="fas fa-plus"></i></a> -->
                  </div>
                </div>
              </div>
              @include('snippets.errors')
              @include('snippets.flash')

              <div class="table-responsive">
                <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                  <thead class="student-thread">
                    <tr>

                      <th >SNo.
                      <th >Category Name</th>
                      <th >Status</th>
                      <th >Date Created</th>
                      <th class="text-end">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($blog_category)){

                      $i = 1;
                      foreach($blog_category as $new){

                        // $category = \App\Models\Category::where('id',$new->category_id)->first();
                        ?>
                        <tr>

                          <td>{{$i++}}</td>

                          <td>{{$new->category_name ?? ''}}</td>
                          <td>
                            <select id='change_blog_category_status{{$new->id}}' class="form-control" onchange='change_blog_category_status({{$new->id}})'>
                              <option value='1'<?php if($new->status == 1) echo 'selected'?>>Active</option>
                              <option value='0'<?php if($new->status == 0) echo 'selected'?>>InActive</option>
                            </select>


                          </td>

                          <td>{{date('d M Y',strtotime($new->created_at))}}</td>


                          <td class="text-end">
                            <div class="actions ">
                              <a data-bs-toggle="modal" data-bs-target="#updateBlogCategory{{$new->id}}" class="btn btn-sm bg-danger-light"> <i class="feather-edit"></i></a>


                              <div id="updateBlogCategory{{$new->id}}" class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-modal="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h4 class="modal-title" id="standard-modalLabel">{{$new->category_name??''}}</h4>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{route('admin.blog_category.update_blog_category')}}" method="post" enctype="multipart/form-data">
                                      @csrf
                                      <input type="hidden" name="id" value="{{$new->id}}">
                                      <div class="modal-body">
                                        <div class="row">
                                          <div class="col-12">
                                            <div class="form-group">
                                              <label for="userName"> Keywords</label>
                                              <input type="text" name="keywords" value="{{ $new->keywords??'' }}" id="keywords" class="form-control"  maxlength="255" placeholder="Enter keywords" />
                                            </div>
                                          </div>

                                          <div class="col-12">
                                            <div class="form-group">
                                              <label for="userName">Robots</label>
                                              <select name="robots" class="form-control">
                                                <option value="index" <?php if($new->robots == 'index') echo "selected";?>>Index</option>
                                                <option value="follow" <?php if($new->robots == 'follow') echo "selected";?>>Follow</option>
                                                <option value="index, follow" <?php if($new->robots == 'index, follow') echo "selected";?>>Both</option>
                                                <option value="noindex, nofollow" <?php if($new->robots == 'noindex, nofollow') echo "selected";?>>No Index , No Follow</option>
                                              </select>

                                            </div>
                                          </div>

                                          <div class="col-12 col-sm-12">
                                            <div class="form-group">
                                             <label for="userName"> Canonical</label>
                                             <input type="text" name="canonical" value="{{ $new->canonical??'' }}" id="canonical" class="form-control"  maxlength="255" placeholder="Enter canonical" />
                                           </div>
                                         </div>

                                         <div class="col-md-12">
                                          <div class="form-group">
                                           <label for="userName"> OG Title</label>
                                           <input type="text" name=" og_title" value="{{ $new->og_title??'' }}" id=" og_title" class="form-control"  maxlength="255" placeholder="Enter  og_title" />
                                         </div>
                                       </div>


                                       <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="userName"> OG Description</label>
                                          <input type="text" name="og_description" value="{{ $new->og_description }}" id="og_description" class="form-control"  maxlength="255" placeholder="Enter og_description" />
                                        </div>
                                      </div>



                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="userName">Meta Title</label>
                                          <input type="text" name="meta_title" class="form-control" value="{{ $new->meta_title??'' }}">
                                        </div>
                                      </div>

                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="userName">Meta Description</label>
                                          <textarea name="meta_description" class="form-control" placeholder="Meta Description">{{ $new->meta_description??''}}</textarea>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>




















                          &nbsp;&nbsp;&nbsp;
                          <a href="{{ route($routeName.'.blog_category.delete', $new->id.'?back_url='.$BackUrl) }}" onclick="return confirm('Are You Want to Delete This')" class="btn btn-sm bg-danger-light">
                            <i class="feather-trash"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php }}?>
                </tbody>
              </table>
              {{ $blog_category->appends(request()->input())->links('admin.pagination') }}

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection

<script>

  function change_blog_category_status(id){
    var status = $('#change_blog_category_status'+id).val();


    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.blog_category.change_blog_category_status') }}",
      type: "POST",
      data: {id:id, status:status},
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
        if(resp.success){
          alert(resp.message);
        }else{
          alert(resp.message);

        }
      }
    });


  }

  function fill_value(val){
  
    var _token = '{{ csrf_token() }}';

    $.ajax({
      url: "{{ route($routeName.'.blog_category.get_category_name') }}",
      type: "POST",
      data: {id:val},
      dataType:"HTML",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
           $('#category_name').val(resp);
      }
    });


  }




</script>