import { Injectable } from "@nestjs/common";
import { BuildingDto } from "./dto/building.dto";

@Injectable()
export class GameService {
  async getThroneRoom(): Promise<any> {
    // Stub implementation – replace with real logic
    return {
      title: "Throne Room",
      description: "A grand hall where decisions are made.",
    };
  }

  async createBuilding(buildingDto: BuildingDto): Promise<any> {
    // Stub implementation – in a real application this would persist the building
    return {
      success: true,
      building: {
        id: Math.random().toString(36).substring(2, 15),
        ...buildingDto,
      },
    };
  }
}
