import { GameService } from "./game.service";
import { BuildingDto } from "./dto/building.dto";
export declare class GameController {
    private readonly gameService;
    constructor(gameService: GameService);
    getThroneRoom(): Promise<any>;
    createBuilding(buildingDto: BuildingDto): Promise<any>;
}
