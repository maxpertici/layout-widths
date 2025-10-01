Add custom settings in your theme.json file

````json
{

    ...

    "custom" : {

			"layoutWidths": {
				
				"widths" : {
					
					"wide" : {
						"label" : "Wide Width",
						"value" : "1340px"
					},
					"wider" : {
						"label" : "Wider Width",
						"value" : "1600px"
					},
					"full" : {
						"label" : "Full Width",
						"value" : "none"
					}
				},

				"labels" : {

					"fr-FR" : {

						"wide"  : "Grande Largeur",
						"wider" : "Plus Grande Largeur",
						"full"  : "Pleine largeur"
					}
				}
			}
		}

}
```