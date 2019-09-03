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
        'datasets' => [],
        'labels'   => [],
        'type'     => 'openstreet',
        'options'  => [],
        'size'     => ['width' => null, 'height' => null]
    ];

    /**
     * @var array
     */
    private $types = [
        'openstreet' => ['mapname' => 'openstreet','maplink' => 'http://{s}.tile.osm.org/{z}/{x}/{y}.png','attribution' => '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'],
        'opentopo' => ['mapname' => 'opentopo','maplink' => 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png','attribution' => 'Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)'],
        'openmapsurfer' => ['mapname' => 'openmapsurfer','maplink' => 'https://maps.heigit.org/openmapsurfer/tiles/roads/webmercator/{z}/{x}/{y}.png','attribution' => 'Imagery from <a href="http://giscience.uni-hd.de/">GIScience Research Group @ University of Heidelberg</a> | Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'],
        'mapbox' => ['mapname' => 'mapbox','maplink' => 'http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}','attribution' => 'Imagery from <a href="http://giscience.uni-hd.de/">GIScience Research Group @ University of Heidelberg</a> | Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors']
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
     * @param array $datasets
     *
     * @return Builder
     */
    public function datasets($datasets)
    {
        return $this->set('datasets', $datasets);
    }

    /**
     * @param $type
     *
     * @return Builder
     */
    public function type($type)
    {
        $mapname = array();
        foreach($this->types as $maptype){
            $mapname[] = $maptype['mapname'];
            if($type == $maptype['mapname']){
                $mapsdetails = [
                    'maplink' => $maptype['maplink'],
                    'attribution' => $maptype['attribution']
                ];
            }
        }

        if (!in_array($type, $mapname)) {
            throw new \InvalidArgumentException('Invalid Map type.');
        }
        return $this->set('type', $mapsdetails);
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
                ->with('type', $map['type'])
                ->with('size', $map['size']);
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
            ->with('type', $map['type'])
            ->with('size', $map['size']);
    }

    public function mapFunctions()
    {
        $map = $this->maps[$this->name];
        dd($map['datasets']);
        return view('map-template::map-template-functions')
        ->with('datasets', $map['datasets']);
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
