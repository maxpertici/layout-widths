# Layout Widths

Plugin WordPress pour gérer les largeurs de mise en page personnalisées dans l'éditeur de blocs.

## Démo

https://github.com/user-attachments/assets/c7f93998-7923-4ee4-9850-bd7c5de6f9c0


## Configuration

Ajoutez les paramètres personnalisés dans votre fichier `theme.json` :

```json
{
	"settings": {
		"custom": {
			"layoutWidths": {
				"widths": {
					"x-content": {
						"label": "X Content",
						"value": "820px"
					},
					"wide": {
						"label": "Wide",
						"value": "1280px"
					},
					"x-wide": {
						"label": "X Wide",
						"value": "1440px"
					},
					"full": {
						"label": "Full",
						"value": "none"
					}
				},
				"labels": {
					"fr-FR": {
						"x-content": "Contenu large",
						"wide": "Large",
						"x-wide": "Très large",
						"full": "Pleine largeur"
					}
				}
			}
		}
	}
}
```
