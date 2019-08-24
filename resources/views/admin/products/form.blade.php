@inject('productPresenter', '\App\Presenters\ProductPresenter')

<div id="vue-product-form" class="box box-info" v-cloak>
  <div class="box-header with-border">
    <h3 class="box-title">{{ !$product->id ? '創建' : '編輯' }}</h3>
    <div class="box-tools">
      @if ($product->id)
      <div class="btn-group pull-right" style="margin-right: 5px">
        <a href="javascript:void(0);" class="btn btn-sm btn-danger btn-delete" title="刪除">
          <i class="fa fa-trash"></i> <span class="hidden-xs">刪除</span>
        </a>
      </div>
      @endif
      <div class="btn-group pull-right" style="margin-right: 5px">
        <a href="{{ admin_url('products') }}" class="btn btn-sm btn-default" title="列表"><i class="fa fa-list"></i> <span class="hidden-xs">列表</span></a>
      </div>
    </div>
  </div>
  <!-- /.box-header -->

  <!-- form start -->
  <form action="{{ admin_url('products' . ($product->id ? '/' . $product->id : '')) }}" method="post" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
    @csrf
    @if ($product->id)
      @method('PUT')
    @endif

    <div class="box-body">
      <div class="fields-group">
        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
          <label for="title" class="col-sm-2 control-label">商品名稱</label>
          <div class="col-sm-8">
            @include('admin.fields.errors', ['key' => 'title'])
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
              <input type="text" id="title" name="title" value="{{ old('title', $product->title) }}" class="form-control title" placeholder="輸入 商品名稱" />
            </div>
          </div>
        </div>

        <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
          <label for="image" class="col-sm-2 control-label">封面圖片</label>
          <div class="col-sm-8">
            @include('admin.fields.errors', ['key' => 'image'])
            <input type="file" class="image" name="image" @if($product->image) data-initial-preview="{{ $product->image }}" data-initial-caption="{{ basename($product->image) }}" @endif />
          </div>
        </div>

        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
          <label for="description" class="col-sm-2 control-label">商品介紹</label>
          <div class="col-sm-8">
            @include('admin.fields.errors', ['key' => 'description'])
            <textarea class="form-control description" id="description" name="description" placeholder="輸入 商品介紹">{{ old('description', $product->description) }}</textarea>
          </div>
        </div>

        <div class="form-group {{ $errors->has('on_sale') ? 'has-error' : '' }}">
          <label for="on_sale" class="col-sm-2 control-label">是否上架</label>
          <div class="col-sm-8">
            @include('admin.fields.errors', ['key' => 'on_sale'])
            <div>
              <input type="checkbox" class="on_sale la_checkbox" @if(!is_null(old('on_sale')) ? old('on_sale') === 'on' : $product->on_sale) checked @endif />
              <input type="hidden" name="on_sale" value="{{ old('on_sale') ?? ($product->on_sale ? 'on' : 'off') }}" />
            </div>
          </div>
        </div>

        <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
          <label for="price" class="col-sm-2 control-label">價格</label>
          <div class="col-sm-8">
            @include('admin.fields.errors', ['key' => 'price'])
            <div v-show="!showMinPrice" class="input-group">
              <input style="width: 100px" type="text" id="price" name="price" value="{{ old('price', $product->price) ?? 0 }}" class="form-control price" placeholder="輸入 價格" min="0" />
            </div>
            <p v-show="showMinPrice" class="form-control-static">$@{{ minPrice }}</p>
            <span class="help-block">
              <i class="fa fa-fw fa-info-circle"></i>&nbsp;如果有設定 SKU 時，商品價格將會自動更新為 SKU 裡最低的價格。
            </span>
          </div>
        </div>

        <product-sku inline-template :init-attrs='@json(old('attrs', $product->attrs))' :init-skus='@json(($productPresenter->skus($product)))' @sku-min-price="onSkuMinPriceUpdated">
          <div>
            <div class="row">
              <div class="col-sm-2"><h4 class="pull-right">商品 SKU 設定</h4></div>
              <div class="col-sm-8"></div>
            </div>

            <hr style="margin-top: 0px;">

            @if ($errors->hasAny(['attrs', 'attrs.*.name', 'attrs.*.items', 'attrs.*.items.*']))
            <div class="form-group has-error">
              <label class="col-sm-2 control-label"></label>
              <div class="col-sm-8">
                @include('admin.fields.errors', ['key' => ['attrs', 'attrs.*.name', 'attrs.*.items', 'attrs.*.items.*']])
              </div>
            </div>
            @endif

            <div v-for="(attr, attr_index) in attrs" :key="attr_index">
              <input type="hidden" :name="`attrs[${attr_index}][id]`" :value="attr.id">

              <div class="form-group">
                <label class="col-sm-2 control-label">SKU 欄位名稱</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" :name="`attrs[${attr_index}][name]`" placeholder="例：大小、顏色、型號...等" v-model="attr.name" :ref="`attrs[${attr_index}][name]`" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label">SKU 欄位選項</label>
                <div class="col-sm-8">
                  <div class="form-group" v-for="(item, item_index) in attr.items" :key="item_index">
                    <div class="col-sm-12">
                      <div class="input-group input-group-sm">
                        <input type="text" class="form-control" :name="`attrs[${attr_index}][items][${item_index}]`" v-model="attr.items[item_index]" :ref="`attrs[${attr_index}][items][${item_index}]`" />
                        <span class="input-group-btn">
                          <button type="button" class="btn btn-warning" @click="removeAttrItem(attr_index,item_index)"><i class="fa fa-fw fa-trash"></i>&nbsp;刪除選項</button>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div>
                    <button type="button" class="btn btn-success btn-sm pull-right" @click="addAttrItem(attr_index)"><i class="fa fa-fw fa-plus"></i>&nbsp;新增選項</button>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-8">
                  <button type="button" class="btn btn-danger btn-sm pull-right" @click="removeAttr(attr_index)"><i class="fa fa-fw fa-trash"></i>&nbsp;刪除欄位</button>
                </div>
              </div>

              <hr>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label"></label>
              <div class="col-sm-8">
                <button type="button" class="btn btn-success btn-sm pull-right" @click="addAttr"><i class="fa fa-fw fa-plus"></i>&nbsp;新增欄位</button>
              </div>
            </div>

            <hr>

            <div class="form-group {{ $errors->hasAny(['skus', 'skus.*.price', 'skus.*.stock', 'skus.*.attr_items_index']) ? 'has-error' : '' }}">
              <label for="price" class="col-sm-2 control-label">商品 SKU</label>
              <div class="col-sm-8">
                @include('admin.fields.errors', ['key' => ['skus', 'skus.*.price', 'skus.*.stock', 'skus.*.attr_items_index']])
                <product-sku-tab :attrs="attrs" :skus="skus" :tabs="tabsData" prefix="product-sku"></product-sku-tab>
              </div>
            </div>
          </div>
        </product-sku>
      </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
      <div class="col-md-2"></div>
      <div class="col-md-8">
        <div class="btn-group pull-right">
          <button type="submit" class="btn btn-primary">送出</button>
        </div>
        <label class="pull-right" style="margin: 5px 10px 0 0;">
          <input type="checkbox" class="after-submit" name="after-save" value="1"> 繼續編輯
        </label>
        <label class="pull-right" style="margin: 5px 10px 0 0;">
          <input type="checkbox" class="after-submit" name="after-save" value="2"> 查看
        </label>
        <div class="btn-group pull-left">
          <button type="reset" class="btn btn-warning">重置</button>
        </div>
      </div>
    </div>
    <!-- /.box-footer -->
  </form>
</div>

<script>
  function init() {
    const token = '{{ csrf_token() }}';

    $('.btn-delete').unbind('click').click(function () {
      swal({
        title: '確認刪除？',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: '確認',
        showLoaderOnConfirm: true,
        cancelButtonText: '取消',
        preConfirm: function () {
          return new Promise(function (resolve) {
            $.ajax({
              method: 'post',
              url: '{{ admin_url('products/' . $product->id) }}',
              data: {
                _method: 'delete',
                _token: token,
              },
              success: function (data) {
                $.pjax({
                  container: '#pjax-container',
                  url: '{{ admin_url('products') }}'
                });
                resolve(data);
              }
            });
          });
        }
      }).then(function (result) {
        const data = result.value;
        if (typeof data === 'object') {
          swal(data.message, '', data.status ? 'success' : 'error');
        }
      });
    });

    $('input.image').fileinput({
      overwriteInitial: true,
      initialPreviewAsData: true,
      browseLabel: '瀏覽',
      showRemove: false,
      showUpload: false,
      deleteExtraData: {
        image: '_file_del_',
        _file_del_: '',
        _token: token,
        _method: 'DELETE'
      },
      deleteUrl: '{{ admin_url('products/' . $product->id) }}',
      allowedFileTypes: ['image']
    });

    if (!document.getElementById('cke_description')) {
      CKEDITOR.replace('description');
    }

    $('.on_sale.la_checkbox').bootstrapSwitch({
      size:'small',
      onText: '是',
      offText: '否',
      onColor: 'primary',
      offColor: 'default',
      onSwitchChange: function (event, state) {
        $(event.target).closest('.bootstrap-switch').next().val(state ? 'on' : 'off').change();
      }
    });

    $('.price:not(.initialized)')
      .addClass('initialized')
      .bootstrapNumber({
        upClass: 'success',
        downClass: 'primary',
        center: true
      });

    $('.after-submit').iCheck({ checkboxClass: 'icheckbox_minimal-blue' })
      .on('ifChecked', function () {
        $('.after-submit').not(this).iCheck('uncheck');
      });
  }
</script>
