<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231010123456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add initial batch of cycling and fitness products';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            INSERT INTO Product (name, description, price, stock, image_url, category, sku, availability, discount, brand, rating, reviews, created_at) VALUES
            ('Siroko K3 Crystal', 'Gafas de sol deportivas con lentes polarizadas.', 59.99, 120, 'https://siroko.com/img/k3crystal.jpg', 'Gafas deportivas', 'K3-001', 'En stock', '10', 'Siroko', 4.8, 320, NOW()),
            ('Siroko K3s Black', 'Diseño ultraligero y protección UV400.', 49.99, 75, 'https://siroko.com/img/k3sblack.jpg', 'Gafas deportivas', 'K3-002', 'En stock', '15', 'Siroko', 4.7, 210, NOW()),
            ('Siroko Tech Hoodie Grey', 'Sudadera técnica para deportistas.', 39.99, 50, 'https://siroko.com/img/techhoodie.jpg', 'Ropa deportiva', 'TH-003', 'En stock', '20', 'Siroko', 4.6, 150, NOW()),
            ('Siroko K2 Navy Blue', 'Gafas casual con estilo clásico.', 29.99, 90, 'https://siroko.com/img/k2navy.jpg', 'Gafas casual', 'K2-004', 'En stock', '5', 'Siroko', 4.4, 100, NOW()),
            ('Siroko Cycling Jersey Pro', 'Maillot ciclista profesional transpirable.', 89.99, 45, 'https://siroko.com/img/cyclingpro.jpg', 'Ropa ciclismo', 'CJ-005', 'En stock', '25', 'Siroko', 4.9, 500, NOW()),
            ('Siroko UV400 Pink', 'Gafas de sol para mujer, elegantes y seguras.', 34.99, 60, 'https://siroko.com/img/uv400pink.jpg', 'Gafas mujer', 'UV-006', 'En stock', '10', 'Siroko', 4.5, 180, NOW()),
            ('Siroko Winter Gloves', 'Guantes térmicos para invierno.', 19.99, 200, 'https://siroko.com/img/gloveswinter.jpg', 'Accesorios', 'WG-007', 'En stock', '30', 'Siroko', 4.8, 300, NOW()),
            ('Siroko K3s Gold Edition', 'Edición especial con detalles dorados.', 69.99, 40, 'https://siroko.com/img/k3sgold.jpg', 'Gafas deportivas', 'K3-008', 'Agotado', '0', 'Siroko', 4.9, 400, NOW()),
            ('Siroko Performance Socks', 'Calcetines deportivos anti-rozaduras.', 12.99, 300, 'https://siroko.com/img/sockssport.jpg', 'Accesorios', 'PS-009', 'En stock', '15', 'Siroko', 4.6, 120, NOW()),
            ('Siroko Cycling Bib Shorts', 'Culotte ciclista con compresión.', 79.99, 20, 'https://siroko.com/img/bibshorts.jpg', 'Ropa ciclismo', 'BS-010', 'En stock', '5', 'Siroko', 4.7, 250, NOW()),
            ('Siroko K3 Avalanche', 'Gafas de sol para montaña con lentes fotocromáticas.', 69.99, 120, 'https://siroko.com/img/k3avalanche.jpg', 'Gafas deportivas', 'K3-011', 'En stock', '10', 'Siroko', 4.9, 350, NOW()),
            ('Siroko K3s Phoenix', 'Gafas deportivas ultraligeras con lentes espejo rojas.', 74.99, 80, 'https://siroko.com/img/k3sphoenix.jpg', 'Gafas deportivas', 'K3S-012', 'En stock', '20', 'Siroko', 4.8, 410, NOW()),
            ('Siroko Nordic Pro Jacket', 'Chaqueta térmica para ciclistas con protección contra el viento.', 99.99, 30, 'https://siroko.com/img/nordicjacket.jpg', 'Ropa ciclismo', 'NPJ-013', 'En stock', '15', 'Siroko', 4.7, 220, NOW()),
            ('Siroko Titanium Ultralight', 'Gafas de titanio ultraligeras con máxima resistencia.', 89.99, 60, 'https://siroko.com/img/titaniumultra.jpg', 'Gafas premium', 'TIU-014', 'En stock', '5', 'Siroko', 4.9, 300, NOW()),
            ('Siroko Alpine 2.0 Hoodie', 'Sudadera técnica para climas fríos y rutas de montaña.', 49.99, 45, 'https://siroko.com/img/alpinehoodie.jpg', 'Ropa deportiva', 'AH2-015', 'Agotado', '0', 'Siroko', 4.6, 180, NOW()),
            ('Siroko Urban Nights', 'Gafas casual para la ciudad con diseño moderno.', 39.99, 70, 'https://siroko.com/img/urbannights.jpg', 'Gafas casual', 'UN-016', 'En stock', '10', 'Siroko', 4.5, 240, NOW()),
            ('Siroko Glacier Snow Gloves', 'Guantes de nieve con aislamiento térmico avanzado.', 29.99, 150, 'https://siroko.com/img/glaciersnow.jpg', 'Accesorios', 'GSG-017', 'En stock', '20', 'Siroko', 4.8, 320, NOW()),
            ('Siroko Mirage Lens Pro', 'Lentes intercambiables con tecnología anti-reflejos.', 59.99, 90, 'https://siroko.com/img/miragelens.jpg', 'Accesorios', 'MLP-018', 'En stock', '15', 'Siroko', 4.7, 290, NOW()),
            ('Siroko Cyclone Windbreaker', 'Cortavientos ligero para ciclismo en condiciones adversas.', 64.99, 40, 'https://siroko.com/img/cyclonewb.jpg', 'Ropa ciclismo', 'CWB-019', 'En stock', '25', 'Siroko', 4.9, 400, NOW()),
            ('Siroko Blaze Sports Cap', 'Gorra deportiva transpirable para entrenamientos intensos.', 19.99, 200, 'https://siroko.com/img/blazecap.jpg', 'Accesorios', 'BSC-020', 'En stock', '10', 'Siroko', 4.6, 190, NOW()),
            ('Siroko Aero Cycling Gloves', 'Guantes aerodinámicos para ciclismo.', 24.99, 150, 'https://siroko.com/img/aerogloves.jpg', 'Accesorios', 'ACG-021', 'En stock', '5', 'Siroko', 4.7, 210, NOW()),
            ('Siroko Pro Cycling Cap', 'Gorra profesional para ciclismo.', 14.99, 180, 'https://siroko.com/img/procap.jpg', 'Accesorios', 'PCC-022', 'En stock', '10', 'Siroko', 4.5, 170, NOW()),
            ('Siroko Thermal Base Layer', 'Capa base térmica para ciclismo.', 34.99, 100, 'https://siroko.com/img/thermalbase.jpg', 'Ropa ciclismo', 'TBL-023', 'En stock', '15', 'Siroko', 4.8, 200, NOW()),
            ('Siroko Windproof Jacket', 'Chaqueta cortavientos para ciclismo.', 79.99, 60, 'https://siroko.com/img/windproofjacket.jpg', 'Ropa ciclismo', 'WPJ-024', 'En stock', '20', 'Siroko', 4.9, 250, NOW()),
            ('Siroko Reflective Vest', 'Chaleco reflectante para ciclismo.', 29.99, 140, 'https://siroko.com/img/reflectivevest.jpg', 'Accesorios', 'RV-025', 'En stock', '10', 'Siroko', 4.6, 180, NOW()),
            ('Siroko Compression Socks', 'Calcetines de compresión para ciclismo.', 19.99, 200, 'https://siroko.com/img/compressionsocks.jpg', 'Accesorios', 'CS-026', 'En stock', '5', 'Siroko', 4.7, 220, NOW()),
            ('Siroko Pro Cycling Shoes', 'Zapatillas profesionales para ciclismo.', 129.99, 50, 'https://siroko.com/img/proshoes.jpg', 'Ropa ciclismo', 'PCS-027', 'En stock', '25', 'Siroko', 4.9, 300, NOW()),
            ('Siroko K3s Carbon Edition', 'Gafas deportivas con montura de carbono.', 79.99, 30, 'https://siroko.com/img/k3scarbon.jpg', 'Gafas deportivas', 'K3-028', 'En stock', '10', 'Siroko', 4.8, 150, NOW()),
            ('Siroko K3s Blue Edition', 'Gafas deportivas con montura azul.', 49.99, 60, 'https://siroko.com/img/k3sblue.jpg', 'Gafas deportivas', 'K3-029', 'En stock', '15', 'Siroko', 4.7, 180, NOW()),
            ('Siroko K3s Red Edition', 'Gafas deportivas con montura roja.', 49.99, 60, 'https://siroko.com/img/k3sred.jpg', 'Gafas deportivas', 'K3-030', 'En stock', '15', 'Siroko', 4.7, 180, NOW()),
            ('M2 Gravel', 'Maillot ciclista de montaña con bolsillos traseros.', 69.99, 40, 'https://siroko.com/img/m2gravel.jpg', 'Ropa ciclismo', 'M2-031', 'En stock', '10', 'Siroko', 4.8, 200, NOW()),
            ('M2 Pro Jersey', 'Maillot ciclista profesional con tejido transpirable.', 79.99, 30, 'https://siroko.com/img/m2pro.jpg', 'Ropa ciclismo', 'M2-032', 'En stock', '20', 'Siroko', 4.9, 250, NOW()),
            ('M2 Pro Bib Shorts', 'Culotte ciclista profesional con badana de gel.', 89.99, 20, 'https://siroko.com/img/m2probib.jpg', 'Ropa ciclismo', 'M2-033', 'En stock', '15', 'Siroko', 4.9, 150, NOW()),
            ('M3 Gran Fondo', 'Maillot ciclista de larga distancia con bolsillos extras.', 79.99, 30, 'https://siroko.com/img/m3granfondo.jpg', 'Ropa ciclismo', 'M3-034', 'En stock', '10', 'Siroko', 4.8, 180, NOW()),
            ('M3 Pro Aero Jersey', 'Maillot ciclista profesional con tejido aerodinámico.', 89.99, 20, 'https://siroko.com/img/m3proaero.jpg', 'Ropa ciclismo', 'M3-035', 'En stock', '20', 'Siroko', 4.9, 220, NOW()),
            ('M3 Pro Aero Bib Shorts', 'Culotte ciclista profesional con tejido compresivo.', 99.99, 10, 'https://siroko.com/img/m3proaerobib.jpg', 'Ropa ciclismo', 'M3-036', 'En stock', '25', 'Siroko', 4.9, 180, NOW()),
            ('M4 Pro Team Jersey', 'Maillot ciclista profesional con diseño de equipo.', 79.99, 30, 'https://siroko.com/img/m4proteam.jpg', 'Ropa ciclismo', 'M4-037', 'En stock', '10', 'Siroko', 4.8, 200, NOW()),
            ('M4 Pro Team Bib Shorts', 'Culotte ciclista profesional con badana de alta densidad.', 89.99, 20, 'https://siroko.com/img/m4proteamshorts.jpg', 'Ropa ciclismo', 'M4-038', 'En stock', '15', 'Siroko', 4.9, 150, NOW()),
            ('SRX PRO HighTech', 'Maillot de manga corta hombre ultraligero', 79.99, 30, 'https://siroko.com/img/srxprohightech.jpg', 'Ropa ciclismo', 'SRX-039', 'En stock', '10', 'Siroko', 4.8, 180, NOW()),
            ('SRX PRO Aero Jersey', 'Maillot de manga corta hombre aerodinámico', 89.99, 20, 'https://siroko.com/img/srxproaero.jpg', 'Ropa ciclismo', 'SRX-040', 'En stock', '20', 'Siroko', 4.9, 220, NOW()),
            ('SRX PRO Aero Bib Shorts', 'Culotte de ciclismo hombre con tejido compresivo', 99.99, 10, 'https://siroko.com/img/srxproaerobib.jpg', 'Ropa ciclismo', 'SRX-041', 'En stock', '25', 'Siroko', 4.9, 180, NOW()),
            ('SRX PRO Team Jersey', 'Maillot de manga corta hombre con diseño de equipo', 79.99, 30, 'https://siroko.com/img/srxproteam.jpg', 'Ropa ciclismo', 'SRX-042', 'En stock', '10', 'Siroko', 4.8, 200, NOW()),
            ('SRX PRO Team Bib Shorts', 'Culotte de ciclismo hombre con badana de alta densidad', 89.99, 20, 'https://siroko.com/img/srxproteamshorts.jpg', 'Ropa ciclismo', 'SRX-043', 'En stock', '15', 'Siroko', 4.9, 150, NOW()),
            ('SRX PRO Aero Gloves', 'Guantes de ciclismo hombre con tejido aerodinámico', 24.99, 150, 'https://siroko.com/img/srxprogloves.jpg', 'Accesorios', 'SRX-044', 'En stock', '5', 'Siroko', 4.7, 210, NOW()),
            ('SRX PRO Aero Cap', 'Gorra de ciclismo hombre con tejido transpirable', 14.99, 180, 'https://siroko.com/img/srxprocap.jpg', 'Accesorios', 'SRX-045', 'En stock', '10', 'Siroko', 4.5, 170, NOW()),
            ('SRX PRO Aero Socks', 'Calcetines de ciclismo hombre con tejido compresivo', 19.99, 200, 'https://siroko.com/img/srxprosocks.jpg', 'Accesorios', 'SRX-046', 'En stock', '5', 'Siroko', 4.7, 220, NOW()),
            ('SRX PRO Aero Shoes', 'Zapatillas de ciclismo hombre con suela de carbono', 129.99, 50, 'https://siroko.com/img/srxproshoes.jpg', 'Ropa ciclismo', 'SRX-047', 'En stock', '25', 'Siroko', 4.9, 300, NOW())
        ");
    }
}