import { Controller, Get, Post, Body } from "@nestjs/common";
import { GameService } from "./game.service";
import { BuildingDto } from "./dto/building.dto";

@Controller("game")
export class GameController {
  constructor(private readonly gameService: GameService) {}

  @Get("throne-room")
  async getThroneRoom() {
    return this.gameService.getThroneRoom();
  }

  @Post("buildings")
  async createBuilding(@Body() buildingDto: BuildingDto) {
    return this.gameService.createBuilding(buildingDto);
  }
}
