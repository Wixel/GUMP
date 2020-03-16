<?php

declare(strict_types=1);

namespace GUMP;

/**
 * Helpers that are environment dependant.
 */
class Helpers
{
    public static function dot_array_search(string $index, array $array)
	{
		$segments = explode('.', rtrim(rtrim($index, '* '), '.'));

		return self::array_search_dot($segments, $array);
	}

	/**
	 * Used by dot_array_search to recursively search the
	 * array with wildcards.
	 *
	 * @param array $indexes
	 * @param array $array
	 *
	 * @return mixed|null
	 */
	private static function array_search_dot(array $indexes, array $array)
	{
		// Grab the current index
		$currentIndex = $indexes
			? array_shift($indexes)
			: null;

		if (empty($currentIndex) || (! isset($array[$currentIndex]) && $currentIndex !== '*'))
		{
			return null;
		}

		// Handle Wildcard (*)
		if ($currentIndex === '*')
		{
			// If $array has more than 1 item, we have to loop over each.
			if (is_array($array))
			{
				foreach ($array as $key => $value)
				{
					$answer = self::array_search_dot($indexes, $value);

					if ($answer !== null)
					{
						return $answer;
					}
				}

				// Still here after searching all child nodes?
				return null;
			}
		}

		// If this is the last index, make sure to return it now,
		// and not try to recurse through things.
		if (empty($indexes))
		{
			return $array[$currentIndex];
		}

		// Do we need to recursively search this value?
		if (is_array($array[$currentIndex]) && $array[$currentIndex])
		{
			return self::array_search_dot($indexes, $array[$currentIndex]);
		}

		// Otherwise we've found our match!
		return $array[$currentIndex];
	}
}
