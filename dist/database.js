"use strict";
var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    Object.defineProperty(o, k2, { enumerable: true, get: function() { return m[k]; } });
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.startConnection = void 0;
const mongoose_1 = require("mongoose");
const dotenv = __importStar(require("dotenv"));
dotenv.config();
const { USER, PASSWORD, DATABASE } = process.env;
const MONGODB = `mongodb+srv://${USER}:${PASSWORD}@products.gicdg.mongodb.net/${DATABASE}?retryWrites=true&w=majority`;
async function startConnection() {
    try {
        await mongoose_1.connect(MONGODB, {
            useNewUrlParser: true,
            useUnifiedTopology: true,
            useFindAndModify: true
        });
        console.log("Database is connected");
    }
    catch (e) {
        console.log(e);
        console.log("Conexion fallida");
    }
}
exports.startConnection = startConnection;
