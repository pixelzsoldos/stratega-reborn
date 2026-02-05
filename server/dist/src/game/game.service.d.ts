import { BuildingDto } from "./dto/building.dto";
export declare class GameService {
    getThroneRoom(): Promise<any>;
    createBuilding(buildingDto: BuildingDto): Promise<any>;
}
