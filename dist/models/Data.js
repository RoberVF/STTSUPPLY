"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const mongoose_1 = require("mongoose");
const schema = new mongoose_1.Schema({
    name: String,
    price: Number,
    team: String,
    liga: String,
    year: Number,
    top: String,
    type: String,
    imagePath: String,
    imagePath2: String,
    imagePath3: String
});
exports.default = mongoose_1.model("Data", schema);
