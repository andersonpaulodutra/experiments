<?php

//Simulador de migração de páginas 

$old_categories = [
	[
		'id' => 1,
		'parent_id' => 0,
		'name' => 'Esporte'
	],
	[
		'id' => 2,
		'parent_id' => 1,
		'name' => 'Regional'
	],
	[
		'id' => 3,
		'parent_id' => 2,
		'name' => 'Futebol'
	],
	[
		'id' => 4,
		'parent_id' => 0,
		'name' => 'Notícia'
	],
	[
		'id' => 5,
		'parent_id' => 4,
		'name' => 'Região'
	],
	[
		'id' => 6,
		'parent_id' => 5,
		'name' => 'Taubaté'
	],
	[
		'id' => 7,
		'parent_id' => 1,
		'name' => 'Nacional'
	],
	[
		'id' => 8,
		'parent_id' => 7,
		'name' => 'Futebol'
	],
	[
		'id' => 9,
		'parent_id' => 8,
		'name' => 'Copa do Mundo'
	],
];

$new_categories = [
	'Notícias' =>	[
			'Sub-Região',
			'Vale',
			'Região' => [
				'São José',
				'Taubaté'
			]
		],	
	'Cultura' =>	[
			'Tv',
			'Livros',
		],
	'Esporte' => [
		'Regional' => [
			'Futebol'
		],
		'Nacional' => [
			'Futebol',
			'Copa do Mundo'
		]
	]
];
echo "Estrutura Antiga<br>";
echo "<pre>";
print_r(createTree($old_categories));

echo "Estrutura Nova<br>";
echo "<pre>";
print_r($new_categories);

echo "Exemplos de busca..<br>";
echo "Busca Copa do Mundo<br>";
echo "<pre>";
print_r(array_find_deep($new_categories,"Copa do Mundo"));
echo "Busca Futebol<br>";
echo "<pre>";
print_r(array_find_deep($new_categories,"Futebol"));


function array_find_deep($array, $search, $keys = array())
{
    foreach($array as $key => $value) {
        if (is_array($value)) {
            $sub = array_find_deep($value, $search, array_merge($keys, array($key)));
            if (count($sub)) {
                return $sub;
            }
        } elseif ($value === $search) {
            return array_merge($keys, array($key));
        }
    }

    return array();
}

/* Recursive branch extrusion */
function createBranch(&$parents, $children) {
    $tree = array();
    foreach ($children as $child) {
        if (isset($parents[$child['id']])) {
            $child['children'] =
                createBranch($parents, $parents[$child['id']]);
        }
        $tree[] = $child;
    } 
    return $tree;
}

/* Initialization */
function createTree($flat, $root = 0) {
    $parents = array();
    foreach ($flat as $a) {
        $parents[$a['parent_id']][] = $a;
    }
    return createBranch($parents, $parents[$root]);
}
