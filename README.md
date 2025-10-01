# Layout Widths

WordPress plugin to manage custom layout widths in the block editor.

## Demo

https://github.com/user-attachments/assets/c7f93998-7923-4ee4-9850-bd7c5de6f9c0


## Configuration

Add the custom settings to your `theme.json` file:

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
