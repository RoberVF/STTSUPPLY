"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.allTypeTeamProducts = exports.allTypeProducts = exports.productLink = exports.productCards = exports.verDatos = exports.incluirDatos = exports.getData = exports.createData = exports.page = exports.mainPage = void 0;
const Data_1 = __importDefault(require("../models/Data"));
async function mainPage(req, res, page) {
    const teamProduct = await Data_1.default.find({ "top": "True" });
    res.render(`${page}`, {
        teamProduct
    });
}
exports.mainPage = mainPage;
async function page(req, res, page) {
    res.render(`${page}`);
}
exports.page = page;
async function createData(req, res) {
    const { name, price, team, liga, year, top, type, imagePath, imagePath2, imagePath3 } = req.body;
    const newData = {
        name,
        price,
        team,
        liga,
        year,
        top,
        type,
        imagePath,
        imagePath2,
        imagePath3
    };
    const data = new Data_1.default(newData);
    await data.save();
    return res.json({
        message: "Dato guardado correctamente",
        data
    });
}
exports.createData = createData;
// export async function getData(req:Request, res:Response): Promise<Response>{
//     const datas= await Data.find()
//     return res.json(datas)
// }
function getData(req, res) {
    res.redirect("pages/incluirDatos");
}
exports.getData = getData;
async function incluirDatos(req, res) {
    res.render("pages/incluirDatos");
}
exports.incluirDatos = incluirDatos;
async function verDatos(req, res) {
    const todos = await Data_1.default.find();
    res.render("pages/verDatos", {
        todos
    });
}
exports.verDatos = verDatos;
async function productCards(req, res, equipo, liga) {
    const teamProduct = await Data_1.default.find({ "team": `${equipo}` });
    const ligaConcreta = await Data_1.default.find({ "ligue": `${liga}` });
    res.render(`teams/cards`, {
        ligaConcreta,
        teamProduct
    });
}
exports.productCards = productCards;
async function productLink(req, res) {
    const { id } = req.params;
    const product = await Data_1.default.findById(id);
    res.render('teams/product', { product });
}
exports.productLink = productLink;
async function allTypeProducts(req, res, type) {
    const teamProduct = await Data_1.default.find({ "type": `${type}` });
    res.render(`pages/productCardsType`, {
        teamProduct
    });
}
exports.allTypeProducts = allTypeProducts;
async function allTypeTeamProducts(req, res, type, team) {
    const typeProduct = await Data_1.default.find({ "type": `${type}` });
    const teamProduct = await Data_1.default.find({ "team": `${team}` });
    res.render(`utils/allTypeTeamNavbar`, {
        typeProduct,
        teamProduct
    });
}
exports.allTypeTeamProducts = allTypeTeamProducts;
