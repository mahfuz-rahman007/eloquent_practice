<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Models\Customer;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // $movies = Movie::all()->sortByDesc(function($movie) {
        //     return $movie->ratings->avg('rating');
        // })->take(100);

        $movies = Movie::join('categories', 'categories.id','=','movies.category_id')
                        ->join('ratings', 'ratings.movie_id' , '=', 'movies.id')
                        ->groupBy('movies.id')
                        // ->orderByDesc([ DB::raw('AVG(ratings.rating) as rating') ])
                        ->select([
                            'movies.id',
                            DB::raw('GROUP_CONCAT(distinct movies.title) as title'),
                            DB::raw('GROUP_CONCAT(distinct movies.release_year) as release_year'),
                            DB::raw('GROUP_CONCAT(distinct categories.name) as category_name'),
                            DB::raw('AVG(distinct ratings.rating) as rating'),
                            DB::raw('COUNT(distinct ratings.id) as count'),
                        ])
                        ->take(1)->get();

    $customers = Customer::all();

    return view('home', compact('movies','customers'));
    }

    public function userSave(Request $request){

        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_veryfied_at' => Carbon::now(),
            'role' => \App\Enums\Roles::fromName($request->role)
       ]);

       return redirect()->back();
    }

    public function practice()
    {
        $data = $this->mainArray();
        $widget = [];
        $widgetTitle = [];
        $widgetText = [];
        $widgetAnchor = [];
        $others = [];

        foreach ($data as $key => $value) {
            if(str_contains($key , 'widget_anchor_')){
                $widgetAnchor[$key] = $value;
            } elseif(str_contains($key , 'widget_text_')){
                $widgetText[$key] = $value;
            } elseif (str_contains($key , 'widget_title_')){
                $widgetTitle[$key] = $value;
            } elseif(str_contains($key , 'widget_')){
                $widget[$key] = $value;
            } else {
                $others[$key] = $value;
            }
        }

        $widgetAnchorCss = $this->widgetAnchorCss($widgetAnchor);
        $widgetTextCss = $this->widgetTextCss($widgetText);
        $widgetTitleCss = $this->widgetTitleCss($widgetTitle);
        $widgetCss = $this->widgetCss($widget);

        dd(json_encode($widgetAnchorCss) , json_encode($widgetTextCss) , json_encode($widgetTitleCss) , json_encode($widgetCss));
    }

    public function widgetAnchorCss($widgetAnchor)
    {
        $anchor_color_css = $this->colorCss($widgetAnchor['widget_anchor_color'], $widgetAnchor['widget_anchor_color_transparent'] , 'widget_anchor_color');
        $anchorCss['a'] = $anchor_color_css;

        $hover_anchor_color_css = $this->colorCss($widgetAnchor['widget_anchor_hover_color'], $widgetAnchor['widget_anchor_hover_color_transparent'] , 'widget_anchor_hover_color');
        $anchorCss['a:hover'] = $hover_anchor_color_css;

        return $anchorCss;
    }

    public function widgetTextCss($widgetText)
    {
        $text_color_css = $this->colorCss($widgetText['widget_text_color'], $widgetText['widget_text_color_transparent'] , 'widget_text_color');
        $textCss['span'] = $text_color_css;

        return $textCss;
    }

    public function widgetTitleCss($widgetTitle)
    {
        $title_color_css = $this->colorCss($widgetTitle['widget_title_color'], $widgetTitle['widget_title_color_transparent'] , 'widget_title_color');

        $titleMargin = [];
        $titleMargin['widget_title_margin-top'] = $widgetTitle['widget_title_margin-top'].$widgetTitle['widget_title_margin-unit'];
        $titleMargin['widget_title_margin-right'] = $widgetTitle['widget_title_margin-right'].$widgetTitle['widget_title_margin-unit'];
        $titleMargin['widget_title_margin-bottom'] = $widgetTitle['widget_title_margin-bottom'].$widgetTitle['widget_title_margin-unit'];
        $titleMargin['widget_title_margin-left'] = $widgetTitle['widget_title_margin-left'].$widgetTitle['widget_title_margin-unit'];
        $title_margin_css = $this->splitIntoCss($titleMargin);

        $titlePadding = [];
        $titlePadding['widget_title_padding-top'] = $widgetTitle['widget_title_padding-top'].$widgetTitle['widget_title_padding-unit'];
        $titlePadding['widget_title_padding-right'] = $widgetTitle['widget_title_padding-right'].$widgetTitle['widget_title_padding-unit'];
        $titlePadding['widget_title_padding-bottom'] = $widgetTitle['widget_title_padding-bottom'].$widgetTitle['widget_title_padding-unit'];
        $titlePadding['widget_title_padding-left'] = $widgetTitle['widget_title_padding-left'].$widgetTitle['widget_title_padding-unit'];
        $title_pading_css = $this->splitIntoCss($titlePadding);

        $title_typography_css = (array)json_decode($widgetTitle['widget_title_typography_css']);

        $titleCss['span'] = array_merge($title_color_css,$title_margin_css,$title_pading_css,$title_typography_css);

        return $titleCss;
    }

    public function widgetCss($widget)
    {
        $widget_color_css = $this->colorCss($widget['widget_background-color'], $widget['widget_bg_color_transparent'] , 'widget_background-color');

        $widgetMargin = [];
        $widgetMargin['widget_margin-top'] = $widget['widget_margin-top'].$widget['widget_margin-unit'];
        $widgetMargin['widget_margin-right'] = $widget['widget_margin-right'].$widget['widget_margin-unit'];
        $widgetMargin['widget_margin-bottom'] = $widget['widget_margin-bottom'].$widget['widget_margin-unit'];
        $widgetMargin['widget_margin-left'] = $widget['widget_margin-left'].$widget['widget_margin-unit'];
        $widget_margin_css = $this->splitIntoCss($widgetMargin);

        $widgetPadding = [];
        $widgetPadding['widget_padding-top'] = $widget['widget_padding-top'].$widget['widget_padding-unit'];
        $widgetPadding['widget_padding-right'] = $widget['widget_padding-right'].$widget['widget_padding-unit'];
        $widgetPadding['widget_padding-bottom'] = $widget['widget_padding-bottom'].$widget['widget_padding-unit'];
        $widgetPadding['widget_padding-left'] = $widget['widget_padding-left'].$widget['widget_padding-unit'];
        $widget_pading_css = $this->splitIntoCss($widgetPadding);

        $widgetBorder = [];
        $widgetBorder['widget_border-color'] = $widget['widget_border-color'];
        $widgetBorder['widget_border-top'] = $widget['widget_border-top'].$widget['widget_border-unit'];
        $widgetBorder['widget_border-right'] = $widget['widget_border-right'].$widget['widget_border-unit'];
        $widgetBorder['widget_border-bottom'] = $widget['widget_border-bottom'].$widget['widget_border-unit'];
        $widgetBorder['widget_border-left'] = $widget['widget_border-left'].$widget['widget_border-unit'];
        $widget_border_css = $this->splitIntoCss($widgetBorder);

        if($widget['widget_custom_box'] == '1')
        {
            $widget_box_shadow['box-shadow'] = $widget['widget_box_shadow_css'];
        }

        $widgetCss['widget'] = array_merge($widget_color_css,$widget_margin_css,$widget_pading_css ,$widget_border_css,$widget_box_shadow);

        return $widgetCss;
    }

    public function colorCss($color , $transparent ,  $key)
    {
        if($transparent == '1'){
            $color = 'transparent';
        }
        $data = [];
        $data[$key] = $color;
        return $this->splitIntoCss($data);
    }

    public function splitIntoCss($data)
    {
        $css = [];
        foreach ($data as $key => $value) {
            $array = explode('_',$key);
            $property = end($array);
            $css[$property] = $value;
        }
        return $css;
    }

    public function mainArray()
    {
        return [
        "widget_background-color" => "#05fd29",
        "widget_bg_color_transparent" => "0",

        "widget_custom_box" => "1",
        "widget_box_shadow_css" => "rgba(182,225,25,1)1em 1em 2em 1em",
        "box_shadow_offset_x" => "1",
        "box_shadow_offset_y" => "1",
        "box_shadow_blur_radius" => "2",
        "box_shadow_spread_radius" => "1",
        "box_shadow_opacity" => "1",
        "box_shadow_unit" => "em",
        "box_shadow_color" => "#b6e119",
        "box_shadow_type" => "outside",

        "widget_margin-top" => "72",
        "widget_margin-right" => "14",
        "widget_margin-bottom" => "86",
        "widget_margin-left" => "24",
        "widget_margin-unit" => "em",

        "widget_padding-top" => "28",
        "widget_padding-right" => "55",
        "widget_padding-bottom" => "95",
        "widget_padding-left" => "74",
        "widget_padding-unit" => "px",

        "widget_border-top" => "86",
        "widget_border-right" => "43",
        "widget_border-bottom" => "41",
        "widget_border-left" => "33",
        "widget_border-unit" => "px",
        "widget_border-color" => "#828ad0",

        "widget_title_tag" => "h1",

        "widget_title_typography_google_link" => "https://fonts.googleapis.com/css?family=Glass+Antiqua:wght@1,400&subset=latin-ext&display=swap",
        "widget_title_typography_css" => '{"font-family":"\"Glass Antiqua\", sans-serif","font-style":"normal","font-weight":"400","text-align":"right","text-transform":"capitalize","font-size":"34px"}',
        "widget_title_font_family" => "Glass Antiqua",
        "widget_title_font_weight_style" => "regular",
        "widget_title_font_subsets" => "latin-ext",
        "widget_title_text_align" => "right",
        "widget_title_text_transform" => "capitalize",
        "widget_title_font_size" => "34",
        "widget_title_line_height" => "70",
        "widget_title_word_spacing" => "99",
        "widget_title_letter_spacing" => "86",
        "widget_title_font_color" => "#d13093",

        "widget_title_margin-top" => "27",
        "widget_title_margin-right" => "54",
        "widget_title_margin-bottom" => "95",
        "widget_title_margin-left" => "64",
        "widget_title_margin-unit" => "px",

        "widget_title_padding-top" => "36",
        "widget_title_padding-right" => "17",
        "widget_title_padding-bottom" => "88",
        "widget_title_padding-left" => "72",
        "widget_title_padding-unit" => "px",

        "widget_title_color" => "#71d593",
        "widget_title_color_transparent" => "0",

        "widget_text_color" => "#113769",
        "widget_text_color_transparent" => "0",

        "widget_anchor_color" => "#f2a898",
        "widget_anchor_color_transparent" => "0",

        "widget_anchor_hover_color" => "#a47939",
        "widget_anchor_hover_color_transparent" => "0"
        ];
    }
}
