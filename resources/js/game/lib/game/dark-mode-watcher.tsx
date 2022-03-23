import CharacterInventoryTabs from "../../sections/character-sheet/components/character-inventory-tabs";

/**
 * When dark mode is enabled set the dark_table to true on the table.
 *
 * @param component
 * @type [{component: Table}]
 */
export const watchForDarkModeInventoryChange = (component: CharacterInventoryTabs) => {
    window.setInterval(() => {
        if (window.localStorage.hasOwnProperty('scheme') && component.state.dark_tables !== true) {
            component.setState({
                dark_tables: window.localStorage.scheme === 'dark'
            })
        } else if (!window.localStorage.hasOwnProperty('scheme') && component.state.dark_tables) {
            component.setState({
                dark_tables: false
            });
        }
    }, 10);
}
