export default interface MapState {


        map_url: string;

        map_position: {x: number, y: number};

        character_position: {x: number, y: number};

        locations: { id: number, is_port: boolean, x: number, y: number, name: string }[] | null;

        player_kingdoms: {id: number, x_position: number, y_position: number, color: string, character_id: number}[] | null;

        enemy_kingdoms: {id: number, x_position: number, y_position: number, color: string, character_id: number}[] | null;
}