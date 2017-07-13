<?php

/*
 * iProtector-v4.0 plugin for PocketMine-MP
 * Copyright (C) 2017 Kevin Andrews <https://github.com/kenygamer/iProtector-v4.0>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
*/

namespace kenygamer\iProtector;

use pocketmine\math\Vector3;

class Area
{
    /** @var string */
    private $name;
    /** @var array */
    public $flags;
    /** @var Vector3 */
    private $pos1;
    /** @var Vector3 */
    private $pos2;
    /** @var Level */
    private $level;
    /** @var array */
    private $whitelist;
    /** @var Plugin */
    private $plugin;
    
    public function __construct($name, $flags, $pos1, $pos2, $level, $whitelist, $plugin)
    {
        $this->name      = strtolower($name);
        $this->flags     = $flags;
        $this->pos1      = new Vector3($pos1[0], $pos1[1], $pos1[2]);
        $this->pos2      = new Vector3($pos2[0], $pos2[1], $pos2[2]);
        $this->level     = $level;
        $this->whitelist = $whitelist;
        $this->plugin    = $plugin;
        $this->save();
    }
    
    /**
     * getName()
     *
     * @api
     *
     * Returns area name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * getPos1()
     *
     * @api
     *
     * Returns area position 1
     *
     * @return array
     */
    public function getPos1()
    {
        return array(
            $this->pos1->getX(),
            $this->pos1->getY(),
            $this->pos1->getZ()
        );
    }
    
    /**
     * getPos2()
     *
     * @api
     *
     * Returns area position 2
     *
     * @return srray
     */
    public function getPos2()
    {
        return array(
            $this->pos2->getX(),
            $this->pos2->getY(),
            $this->pos2->getZ()
        );
    }
    
    /**
     * getFlags()
     *
     * @api
     *
     * Return area flags
     *
     * @return array
     */
    public function getFlags()
    {
        return $this->flags;
    }
    
    /**
     * getFlag()
     *
     * @api
     *
     * Checks if area flag is set
     *
     * @param string $flag
     *
     * @return bool
     */
    public function getFlag($flag)
    {
        if (isset($this->flags[$flag])) {
            return $this->flags[$flag];
        }
        return false;
    }
    
    /**
     * setFlag()
     *
     * @api
     *
     * Sets area flag value
     *
     * @param string $flag
     * @param bool $value
     *
     * @return bool
     */
    public function setFlag($flag, $value)
    {
        if (isset($this->flags[$flag])) {
            $this->flags[$flag] = $value;
            $this->plugin->saveAreas();
            return true;
        }
        return false;
    }
    
    /**
     * contains()
     *
     * @api
     *
     * Checks if given position is inside area
     *
     * @param pocketmine\level\Position $pos
     * @param pocketmine\level\Level $level
     *
     * @return bool
     */
    public function contains($pos, $level)
    {
        if ((min($this->pos1->getX(), $this->pos2->getX()) <= $pos->getX()) && (max($this->pos1->getX(), $this->pos2->getX()) >= $pos->getX()) && (min($this->pos1->getY(), $this->pos2->getY()) <= $pos->getY()) && (max($this->pos1->getY(), $this->pos2->getY()) >= $pos->getY()) && (min($this->pos1->getZ(), $this->pos2->getZ()) <= $pos->getZ()) && (max($this->pos1->getZ(), $this->pos2->getZ()) >= $pos->getZ()) && ($this->level == $level)) {
            return true;
        }
        return false;
    }
    
    /**
     * toggleFlag()
     *
     * @api
     *
     * Modifies area flag value
     *
     * @param string $flag
     *
     * @return bool
     */
    public function toggleFlag($flag)
    {
        if (isset($this->flags[$flag])) {
            $this->flags[$flag] = !$this->flags[$flag];
            $this->plugin->saveAreas();
            return $this->flags[$flag];
        }
        return false;
    }
    
    /* Pending getLevel() documentation */
    public function getLevel()
    {
        return $this->level;
    }
    
    /**
     * isWhitelisted()
     *
     * @api
     *
     * Checks if player is inside area whitelist
     *
     * @param string $n Player name
     *
     * @return bool
     */
    public function isWhitelisted($n)
    {
        if (in_array($n, $this->whitelist)) {
            return true;
        }
        return false;
    }
    
    /**
     * setWhitelisted()
     *
     * @api
     *
     * Adds a player into area whitelist
     *
     * @param string $n Player name
     * @param bool $v Add or remove from whitelist
     *
     * @return bool
     */
    public function setWhitelisted($n, $v = true)
    {
        if ($v) {
            if (!in_array($n, $this->whitelist)) {
                array_push($this->whitelist, $n);
                $this->plugin->saveAreas();
                return true;
            }
        } else {
            if (in_array($n, $this->whitelist)) {
                $key = array_search($n, $this->whitelist);
                array_splice($this->whitelist, $key, 1);
                $this->plugin->saveAreas();
                return true;
            }
        }
        return false;
    }
    
    /**
     * getWhitelist()
     *
     * @api
     *
     * Returns area whitelist
     *
     * @return array
     */
    public function getWhitelist()
    {
        return $this->whitelist;
    }
    
    /**
     * save()
     *
     * @api
     *
     * Saves an area
     *
     * @return bool
     */
    public function save()
    {
        $this->plugin->areas[$this->name] = $this;
        return true;
    }
    
    /**
     * delete()
     *
     * @api
     *
     * Deletes an area
     *
     * @return bool
     */
    public function delete()
    {
        unset($this->plugin->areas[$this->getName()]);
        $this->plugin->saveAreas();
        return true;
    }
    
}
