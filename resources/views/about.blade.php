@php
    $url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyB8m91pnDhVEOBYvwl0PUMosUCXKSH-uuk';
    $json = file_get_contents($url);
    $json_data = json_decode($json, true);

    $data = [];

    for ($i = 0; $i < sizeof($json_data['items']); $i++) {
        $item = $json_data['items'][$i];
        $data[$i]['family'] = $item['family'];
        $data[$i]['variants'] = json_encode($item['variants']);
        $data[$i]['subsets'] = json_encode($item['subsets']);
    }

    $fonts = $data;
@endphp

@extends('layouts.app')

@section('custom-styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>

    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="card card-body">
                <form action="" method="post">
                    <div class="form-group row py-4 border-bottom">
                        <div class="col-md-4">
                            <label for="back_to_top_button" class="font-16 bold black">Body Typography
                            </label>
                            <span class="d-block">'These settings control the typography for body.</span>
                        </div>
                        <div class="col-md-7 ml-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="font_family">Font Family</label>
                                        <select name="font_family" class="form-control select font_family"
                                            id="body_font_family" data-section="body">
                                            @foreach ($fonts as $font)
                                                <option value="{{ $font['family'] }}" data-subsets="{{ $font['subsets'] }}"
                                                    data-variations="{{ $font['variants'] }}">{{ $font['family'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="font_weight_style">Font Weight & Style</label>
                                        <select name="font_weight_style" class="form-control select"
                                            id="body_font_weight_style" onchange="createUrl('body')">
                                            <option selected disabled value="">styles</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="font_subsets">Font Subsets</label>
                                        <select name="font_subsets" class="form-control select" id="body_font_subsets"
                                            onchange="createUrl('body')">
                                            <option selected disabled value="">subsets</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="text_align">Text Align</label>
                                        <select name="text_align" class="form-control select" id="body_text_align">
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                            <option value="">4</option>
                                            <option value="">5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="text_transform">Text Transform</label>
                                        <select name="text_transform" class="form-control select" id="body_text_transform">
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                            <option value="">4</option>
                                            <option value="">5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="font_size">Font Size</label>
                                            <input type="number" name="body_font_size" class="form-control" id="font_size"
                                                placeholder="Size">px
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="line_height">Line Height</label>
                                            <input type="number" name="body_line_height" class="form-control"
                                                id="line_height" placeholder="Height">px
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="word_spacing">Word Spacing</label>
                                            <input type="number" name="body_word_spacing" class="form-control"
                                                name="word_spacing" id="word_spacing" placeholder="Word Spacing">px
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="letter_spacing">Letter Spacing</label>
                                            <input type="number" name="body_letter_spacing" class="form-control"
                                                name="letter_spacing" id="letter_spacing" placeholder="Letter Spacing">px
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="font_color">Font Color</label>
                                            <input type="color" class="form-control" id="font_color" name="font_color"
                                                value="#b8e994">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="mt-4" id="body_typography_preview">
                    A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z.
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>

    <!-- ✅ load JS for Select2 ✅ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.font_family').on('change', function() {
                let data_section = $(this).data('section');
                let subsetId = "#" + data_section + "_font_subsets";
                let fontWeigthId = "#" + data_section + "_font_weight_style";

                let variations = $("option:selected", this).data('variations')
                let subsets = $("option:selected", this).data('subsets')

                addedToSubsets(subsetId, subsets);
                addedToFontWeight(fontWeigthId, variations);
                createUrl(data_section);
            })

        });


        // added to subsets
        function addedToSubsets(subsetId, subsets) {
            $(subsetId).html('');
            $(subsets).each(function(index, element) {
                $(subsetId).append(`<option value="${element}">${element}</option>`);
            });
        }

        // added to subsets
        function addedToFontWeight(fontWeigthId, variations) {
            $(fontWeigthId).html('');
            $(variations).each(function(index, element) {
                $(fontWeigthId).append(`<option value="${element}">${element}</option>`);
            });
        }

        function createUrl(section) {
            let family = $('#' + section + '_font_family').val();
            let subset = $('#' + section + '_font_subsets').val();
            let variation = $('#' + section + '_font_weight_style').val();
            let boldNum = '';

            var apiUrl = [];
            apiUrl.push('https://fonts.googleapis.com/css?family=');
            apiUrl.push(family.replace(/ /g, '+'));

            if (variation.includes('italic')) {
                apiUrl.push(':ital');

                boldNum = variation.replace('italic', '');
                if (boldNum.length > 0) {
                    apiUrl.push(',wght@1,' + boldNum);
                } else {
                    boldNum = '400';
                    apiUrl.push('@1');
                }

                var font_style = 'italic';
            } else {
                apiUrl.push(':');
                if (variation == 'regular') {
                    boldNum = '400';
                } else {
                    boldNum = variation;
                }
                apiUrl.push('wght@1,' + boldNum);

                var font_style = 'normal';
            }

            if (subset) {
                apiUrl.push('&subset=');
                apiUrl.push(subset);
            }

            apiUrl.push("&display=swap");
            var url = apiUrl.join('');

            console.log(url);
            // $('head').append(`<link rel="stylesheet" href = "${url}" > `);
            $('link:last').after('<link href="' + url + '" rel="stylesheet" type="text/css">');
            $('#'+section+'_typography_preview').css({
                "font-family":'"'+family+'", sans-serif',
                "font-style":font_style,
                "font-weight":boldNum
            })
        }
    </script>
@endsection
