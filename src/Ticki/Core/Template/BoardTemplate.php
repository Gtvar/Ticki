<?php

namespace Ticki\Core\Template;

use Ticki\Core\Model\Board;
use Ticki\Core\Model\Cell;

/**
 * Class BoardTemplate
 */
class BoardTemplate
{
    /**
     * Simple draw board
     *
     * @param Board $board
     *
     * @return string
     */
    public static function draw(Board $board)
    {
        $out = $line = '';
        $set = $board->getKitCells();

        /** @var \Ticki\Core\Model\Cell $value */
        foreach ($set as $pos => $value) {
	        $template = $pos <= 9 || $value !== null ? "  %s " : " %s ";
            $line .= sprintf($template, $value ? self::formatType($value->getType()) : $pos);

            if ($pos % $board->getSideCount() == 0) {
                $out .= $line . "\r\n";
                $line = '';
            }
        }

        return $out;
    }

    protected static function formatType($type)
    {
        return $type == Cell::TIC ? '<info>' . $type . '</info>' : '<comment>' . $type . '</comment>';
    }
}
