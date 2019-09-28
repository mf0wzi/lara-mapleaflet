<?php

/*
 * This file is inspired by Builder from Laravel ChartJS - Brian Faust
 */

namespace Noonenew\LaravelLeafLet;

class Builder
{
    /**
     * @var array
     */
    private $maps = [];

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $defaults = [
        'datajson'       => [],
        'datasets'       => [],
        'location'       => ['long' => 47.190976, 'lat' => 15.387664],
        'marker'         => [],
        'tooltip'        => [],
        'popup'          => [],
        'zoom'           => ['max' => 18, 'min' => null, 'start' => 6.2],
        'labels'         => [],
        'type'           => [],
        'tile'           => 'openstreet',
        'options'        => [],
        'customfunction' => [],
        'size'           => ['width' => null, 'height' => null]
    ];

    /**
     * @var array
     */
    private $tiles = [
        'openstreet' => ['mapname' => 'openstreet','maplink' => 'http://{s}.tile.osm.org/{z}/{x}/{y}.png','attribution' => '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'],
        'opentopo' => ['mapname' => 'opentopo','maplink' => 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png','attribution' => 'Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'],
        'openmapsurfer' => ['mapname' => 'openmapsurfer','maplink' => 'https://maps.heigit.org/openmapsurfer/tiles/roads/webmercator/{z}/{x}/{y}.png','attribution' => 'Imagery from <a href="http://giscience.uni-hd.de/">GIScience Research Group @ University of Heidelberg</a> | Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'],
        'custom' => ['mapname' => 'custom','maplink' => 'http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}','attribution' => 'Imagery from <a href="http://giscience.uni-hd.de/">GIScience Research Group @ University of Heidelberg</a> | Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors']
    ];

    /**
     * @param $mapid
     *
     * @return $this|Builder
     */
    public function mapid($mapid)
    {
        return $this->set('mapid', $mapid);
    }

    /**
     * @param $name
     *
     * @return $this|Builder
     */
    public function name($name)
    {
        $this->name          = $name;
        $this->maps[$name] = $this->defaults;
        return $this;
    }

    /**
     * @param $element
     *
     * @return Builder
     */
    public function element($element)
    {
        return $this->set('element', $element);
    }

    /**
     * @param array $labels
     *
     * @return Builder
     */
    public function labels(array $labels)
    {
        return $this->set('labels', $labels);
    }

    /**
     * @param string|array $datajson
     *
     * @return Builder
     */
    public function datajson($datajson)
    {
        if (is_array($datajson)) {
            $this->set('datajson', json_encode($datajson, true));
            return $this;
        }

        $this->set('datajson', $datajson);
        return $this;
    }

    /**
     * @param string $datasets
     *
     * @return Builder
     */
    public function datasets($datasets)
    {
        return $this->set('datasets', $datasets);
    }

    /**
     * @param string $type
     *
     * @return Builder
     */
    public function type($type)
    {
        return $this->set('type', $type);
    }

    /**
     * @param array $zoom
     *
     * @return Builder
     */
    public function zoom(array $zoom)
    {
        return $this->set('zoom', $zoom);
    }

    /**
     * @param array $location
     *
     * @return Builder
     */
    public function location(array $location)
    {
        return $this->set('location', $location);
    }

    /**
     * @param $tile
     *
     * @return Builder
     */
    public function tile($tile)
    {
        $mapname = array();
        foreach($this->tiles as $maptile){
            $mapname[] = $maptile['mapname'];
            if($tile == $maptile['mapname']){
                $mapsdetails = [
                    'maplink' => $maptile['maplink'],
                    'attribution' => $maptile['attribution']
                ];
            }
        }

        if (!in_array($tile, $mapname)) {
            throw new \InvalidArgumentException('Invalid Map Tile.');
        }
        return $this->set('tile', $mapsdetails);
    }

    /**
     * @param array $size
     *
     * @return Builder
     */
    public function size($size)
    {
        return $this->set('size', $size);
    }

    /**
     * @param array $options
     *
     * @return $this|Builder
     */
    public function options(array $options)
    {
        foreach ($options as $key => $value) {
            $this->set('options.' . $key, $value);
        }

        return $this;
    }

    /**
     *
     * @param string|array $optionsRaw
     * @return \self
     */
    public function optionsRaw($optionsRaw)
    {
        if (is_array($optionsRaw)) {
            $this->set('optionsRaw', json_encode($optionsRaw, true));
            return $this;
        }

        $this->set('optionsRaw', $optionsRaw);
        return $this;
    }

    /**
     * @param string $marker
     *
     * @return Builder
     */
    public function marker($marker)
    {
        if(is_null($marker)){
            $marker = "L.ExtraMarkers.icon({
                        icon: 'fa-circle',
                        markerColor: 'cyan',
                        shape: 'circle',
                        prefix: 'fa'
                    });";
        } else {
            $marker = $marker;
        }

        return $this->set('marker', $marker);
    }

    /**
     * @param string $tooltip
     *
     * @return Builder
     */
    public function tooltip($tooltip)
    {
        if(is_null($tooltip)){
            $tooltip = "'<p>Please add a <code>ToolTip</code> from the admin side,</p><p> To be able to view if from here!!!</p>'";
        } else {
            $tooltip = $tooltip;
        }
        return $this->set('tooltip', $tooltip);
    }

    /**
     * @param string $popup
     *
     * @return Builder
     */
    public function popup($popup)
    {
        if(is_null($popup)){
            $popup = "'<p>Please add a <code>Popup</code> from the admin side,</p><p> To be able to view if from here!!!</p>'";
        } else {
            $popup = $popup;
        }
        return $this->set('popup', $popup);
    }

    /**
     * @param string $customfunction
     *
     * @return Builder
     */
    public function customfunction($customfunction)
    {
        if(is_null($customfunction)){
            $customfunction = null;
        } else {
            $customfunction = $customfunction;
        }
        return $this->set('customfunction', $customfunction);
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $map = $this->maps[$this->name];
        //dd($map['datasets']);
        return view('map-template::map-template')
                ->with('datasets', $map['datasets'])
                ->with('element', $this->name)
                ->with('labels', $map['labels'])
                ->with('options', isset($map['options']) ? $map['options'] : '')
                ->with('optionsRaw', isset($map['optionsRaw']) ? $map['optionsRaw'] : '')
                ->with('marker', $map['marker'])
                ->with('tooltip', $map['tooltip'])
                ->with('popup', $map['popup'])
                ->with('type', $map['type'])
                ->with('tile', $map['tile'])
                ->with('size', $map['size'])
                ->with('zoom', $map['zoom']);
    }

    public function container()
    {
        $map = $this->maps[$this->name];

        return view('map-template::map-template-without-script')
                ->with('element', $this->name)
                ->with('size', $map['size']);
    }


    public function script()
    {
        $map = $this->maps[$this->name];

        return view('map-template::map-template-script')
            ->with('datasets', $map['datasets'])
            ->with('element', $this->name)
            ->with('labels', $map['labels'])
            ->with('options', isset($map['options']) ? $map['options'] : '')
            ->with('optionsRaw', isset($map['optionsRaw']) ? $map['optionsRaw'] : '')
            ->with('marker', $map['marker'])
            ->with('tooltip', $map['tooltip'])
            ->with('popup', $map['popup'])
            ->with('type', $map['type'])
            ->with('tile', $map['tile'])
            ->with('location', $map['location'])
            ->with('datajson', $map['datajson'])
            ->with('size', $map['size'])
            ->with('zoom', $map['zoom']);
    }

    public function mapFunctions()
    {
        $map = $this->maps[$this->name];
        return view('map-template::map-template-functions')
        ->with('customfunction', $map['customfunction']);
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    private function get($key)
    {
        return array_get($this->maps[$this->name], $key);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this|Builder
     */
    private function set($key, $value)
    {
        array_set($this->maps[$this->name], $key, $value);
        return $this;
    }
}
