<?php
/**
 * File containing test code for the Graph component.
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @package Graph
 * @version //autogentag//
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

class ezcGraphTestCase extends ezcTestImageCase
{
    /**
     * Normalize given PHP code for flash generation
     * 
     * @param string $code 
     * @return string
     */
    protected function normalizeFlashCode( $code )
    {
        return preg_replace(
            array(
                '/\$[sf]\d+/',
                '[/\\*.*\\*/]i',
                '(BitmapID:.*?,SWFFILL_RADIAL_GRADIENT\\);)s',
            ),
            array(
                '$var',
                '/* Comment irrelevant */',
                '/* Inserted bitmap fill */',
            ),
            $code
        );
    }

    /**
     * Compares to flash files comparing the output of `swftophp`
     * 
     * @param string $generated Filename of generated image
     * @param string $compare Filename of stored image
     * @return void
     */
    protected function swfCompare( $generated, $compare )
    {
        $this->assertTrue(
            file_exists( $generated ),
            'No image file has been created.'
        );

        $this->assertTrue(
            file_exists( $compare ),
            'Comparision image does not exist.'
        );

        $executeable = ezcBaseFeatures::findExecutableInPath( 'swftophp' );

        if ( !$executeable )
        {
            $this->markTestSkipped( 'Could not find swftophp executeable to compare flash files. Please check your $PATH.' );
        }

        $this->assertEquals(
            $this->normalizeFlashCode( 
                shell_exec( $executeable . ' ' . escapeshellarg( $compare ) )
            ),
            $this->normalizeFlashCode( 
                shell_exec( $executeable . ' ' . escapeshellarg( $generated ) )
            ),
            'Rendered image is not correct.'
        );
    }

    /**
     * Compares a generated image with a stored file
     * 
     * @param string $generated Filename of generated image
     * @param string $compare Filename of stored image
     * @return void
     */
    protected function compare( $generated, $compare )
    {
        $this->assertXmlFileEqualsXmlFile(
            $generated,
            $compare
        );
    }

    /**
     * Get canned pseudorandom data
     *
     * Get numeric canned data.
     * 
     * @param int $count 
     * @param int $min
     * @param int $max
     * @param int $randomize
     * @return array
     */
    protected function getRandomData( $count, $min = 0, $max = 1000, $randomize = 23 )
    {
        $cannedData = array(
            2 => array( 0.566570, 0.820166, 0.969558, 0.931541, 0.549663, 0.947731, 0.569926, 0.510001, 0.420368, 0.320536, 0.664173, 0.154426, 0.204648, 0.298817, 0.375479, 0.119950, 0.695828, 0.510309, 0.266827, 0.372756, 0.621134, 0.187021, 0.476108, 0.683026, 0.862858, 0.498561, 0.513578, 0.586797, 0.184440, 0.276706, 0.785335, 0.737229, 0.140777, 0.458066, 0.500269, 0.407308, 0.149164, 0.827534, 0.079645, 0.026096, 0.505246, 0.697732, 0.065286, 0.715427, 0.574440, 0.887348, 0.096531, 0.181727, 0.127160, 0.494290, 0.596745, 0.430696, 0.226012, 0.221835, 0.106945, 0.767491, 0.220306, 0.577308, 0.349826, 0.829615, 0.536484, 0.627036, 0.201743, 0.536821, 0.640407, 0.351228, 0.483070, 0.793090, 0.499037, 0.663062, 0.615670, 0.384650, 0.793638, 0.990852, 0.417676, 0.119484, 0.840264, 0.846188, 0.700753, 0.852281, 0.964552, 0.487910, 0.500008, 0.785293, 0.113777, 0.993267, 0.663637, 0.680187, 0.567144, 0.736996, 0.577702, 0.194121, 0.436747, 0.641076, 0.776559, 0.467842, 0.466960, 0.274173, 0.953743, 0.798201, 0.460065, 0.814139, 0.082095, 0.948091, 0.638176, 0.822885, 0.153423, 0.515139, 0.588231, 0.773510, 0.027202, 0.256665, 0.757094, 0.431538, 0.067144, 0.888304, 0.003829, 0.056242, 0.970581, 0.493447, 0.196446, 0.702042, 0.601817, 0.290161, 0.239311, 0.481022, 0.835048, 0.960314, 0.293023, 0.576540, 0.471662, 0.322874, 0.356624, 0.240634, 0.045679, 0.063832, 0.983154, 0.711217, 0.556325, 0.517431, 0.504000, 0.884472, 0.323541, 0.553634, 0.737935, 0.423063, 0.386890, 0.393933, 0.165663, 0.926547, 0.258005, 0.005544, 0.379210, 0.728499, 0.013017, 0.355753, 0.797405, 0.781573, 0.269389, 0.633701, 0.420856, 0.118402, 0.969199, 0.363326, 0.662202, 0.493252, 0.387523, 0.230193, 0.497074, 0.914476, 0.414906, 0.176196, 0.653402, 0.411746, 0.451584, 0.630843, 0.972911, 0.660407, 0.883927, 0.381693, 0.313258, 0.962460, 0.953685, 0.647014, 0.738400, 0.045451, 0.338948, 0.803452, 0.214635, 0.229386, 0.578731, 0.879202, 0.352619, 0.411045, 0.342792, 0.786173, 0.832818, 0.532144, 0.121645, 0.036957,  ),
            12 => array( 0.851088, 0.446222, 0.265198, 0.873388, 0.263315, 0.909049, 0.533739, 0.723602, 0.014574, 0.350478, 0.918747, 0.033964, 0.093793, 0.707861, 0.961329, 0.920773, 0.956950, 0.725954, 0.866086, 0.372834, 0.283828, 0.326565, 0.606083, 0.983648, 0.944226, 0.522743, 0.852736, 0.912539, 0.992247, 0.804876, 0.521226, 0.147974, 0.452236, 0.907268, 0.511083, 0.319214, 0.768134, 0.957729, 0.842580, 0.539675, 0.764561, 0.563141, 0.983462, 0.801266, 0.859540, 0.943178, 0.888001, 0.223359, 0.309897, 0.702940, 0.671453, 0.499945, 0.524253, 0.202660, 0.181267, 0.629365, 0.705898, 0.144298, 0.733126, 0.949613, 0.702623, 0.164368, 0.327569, 0.866227, 0.668894, 0.037428, 0.019377, 0.217641, 0.624582, 0.008189, 0.950314, 0.822884, 0.230203, 0.881692, 0.178286, 0.545464, 0.406640, 0.583524, 0.451308, 0.608258, 0.594118, 0.205081, 0.001323, 0.926741, 0.177564, 0.268576, 0.035082, 0.304234, 0.585023, 0.737563, 0.424052, 0.928917, 0.463149, 0.020572, 0.629818, 0.067143, 0.465508, 0.570751, 0.967150, 0.845358, 0.084272, 0.263902, 0.732521, 0.186931, 0.636200, 0.278410, 0.027907, 0.014498, 0.696291, 0.562049, 0.220852, 0.568726, 0.939486, 0.495029, 0.523246, 0.804790, 0.578138, 0.410221, 0.948486, 0.048588, 0.572405, 0.571325, 0.803517, 0.738478, 0.888717, 0.309035, 0.277117, 0.087841, 0.641678, 0.573393, 0.492737, 0.360905, 0.496983, 0.414960, 0.461440, 0.313781, 0.107819, 0.526338, 0.396543, 0.358776, 0.603356, 0.233218, 0.559425, 0.942160, 0.480047, 0.410475, 0.116800, 0.452528, 0.796748, 0.848016, 0.944582, 0.761540, 0.073470, 0.373991, 0.595153, 0.459003, 0.974342, 0.042212, 0.331446, 0.129395, 0.366155, 0.976776, 0.862466, 0.432013, 0.941638, 0.656146, 0.445627, 0.644109, 0.332363, 0.281786, 0.924331, 0.571449, 0.375082, 0.623844, 0.671922, 0.129392, 0.319476, 0.394108, 0.291456, 0.925676, 0.045145, 0.361869, 0.599534, 0.542748, 0.946556, 0.099611, 0.857191, 0.728208, 0.688928, 0.913297, 0.993173, 0.695674, 0.900104, 0.307650, 0.919865, 0.047576, 0.996410, 0.683707, 0.642926, 0.080634,  ),
            23 => array( 0.480382, 0.669097, 0.050717, 0.819528, 0.765460, 0.781680, 0.714309, 0.573908, 0.773461, 0.441914, 0.686222, 0.818568, 0.829564, 0.099820, 0.604995, 0.219652, 0.618052, 0.170934, 0.593318, 0.426765, 0.993018, 0.754573, 0.112671, 0.006431, 0.884948, 0.000083, 0.300409, 0.028561, 0.408100, 0.419121, 0.978427, 0.681558, 0.845094, 0.243099, 0.930651, 0.578859, 0.294744, 0.394941, 0.715362, 0.479574, 0.822467, 0.390940, 0.626183, 0.329541, 0.886226, 0.756128, 0.000528, 0.833682, 0.942167, 0.172184, 0.141500, 0.212153, 0.583898, 0.874715, 0.346489, 0.563732, 0.125943, 0.876078, 0.574939, 0.470606, 0.828752, 0.703064, 0.717852, 0.230359, 0.119226, 0.010343, 0.399342, 0.676243, 0.129756, 0.524780, 0.926982, 0.362537, 0.831206, 0.082869, 0.464386, 0.131682, 0.843480, 0.839397, 0.547975, 0.704860, 0.588486, 0.967892, 0.773613, 0.904225, 0.349405, 0.292806, 0.557707, 0.856103, 0.825050, 0.854472, 0.240583, 0.954299, 0.506055, 0.994327, 0.605572, 0.706970, 0.520484, 0.514525, 0.955740, 0.973604, 0.095101, 0.119011, 0.499735, 0.110642, 0.174050, 0.292887, 0.827716, 0.027199, 0.772683, 0.804724, 0.916673, 0.771205, 0.537778, 0.778652, 0.123342, 0.970985, 0.856769, 0.412550, 0.772044, 0.795814, 0.917946, 0.468711, 0.129063, 0.562284, 0.078343, 0.617420, 0.759954, 0.173180, 0.615987, 0.564856, 0.951588, 0.139979, 0.749141, 0.965910, 0.450255, 0.974327, 0.972518, 0.928447, 0.239371, 0.481192, 0.798022, 0.043753, 0.687947, 0.696172, 0.845010, 0.247592, 0.541434, 0.989087, 0.903878, 0.783309, 0.622848, 0.496989, 0.921040, 0.406514, 0.950028, 0.420941, 0.651958, 0.616032, 0.099042, 0.489014, 0.798601, 0.592354, 0.612478, 0.704378, 0.092726, 0.359230, 0.870711, 0.131576, 0.462306, 0.067342, 0.497213, 0.261388, 0.503535, 0.704430, 0.871074, 0.781800, 0.203215, 0.684914, 0.596051, 0.099966, 0.757770, 0.551668, 0.459364, 0.259673, 0.928103, 0.305600, 0.393239, 0.497693, 0.435599, 0.851174, 0.006428, 0.236481, 0.046629, 0.330623, 0.418742, 0.458455, 0.118193, 0.328586, 0.433018, 0.017037,  ),
            42 => array(  0.630710, 0.796543, 0.950715, 0.814003, 0.273256, 0.222873, 0.405613, 0.399853, 0.156018, 0.445833, 0.849498, 0.902346, 0.058083, 0.543072, 0.137364, 0.671784, 0.601115, 0.142866, 0.296201, 0.650889, 0.020584, 0.941027, 0.969910, 0.721999, 0.170122, 0.938553, 0.212339, 0.994704, 0.181825, 0.002296, 0.183404, 0.377024, 0.304242, 0.382853, 0.524756, 0.007066, 0.431945, 0.023062, 0.291229, 0.524775, 0.383630, 0.399861, 0.139494, 0.958583, 0.704561, 0.030759, 0.638154, 0.769548, 0.456070, 0.912691, 0.217142, 0.618386, 0.199673, 0.382462, 0.489063, 0.983231, 0.411126, 0.466763, 0.046450, 0.859941, 0.607545, 0.315419, 0.170524, 0.553772, 0.930433, 0.991986, 0.948886, 0.061092, 0.037662, 0.563288, 0.189038, 0.610310, 0.700878, 0.978542, 0.097672, 0.230894, 0.319306, 0.241025, 0.440152, 0.683264, 0.881502, 0.609997, 0.501282, 0.171320, 0.962315, 0.173364, 0.087384, 0.391061, 0.258780, 0.182236, 0.340040, 0.249156, 0.311711, 0.571303, 0.483227, 0.207941, 0.458540, 0.436571, 0.819420, 0.963193, 0.969585, 0.842285, 0.222548, 0.553543, 0.939499, 0.608391, 0.107491, 0.926659, 0.597900, 0.268457, 0.921875, 0.676022, 0.088492, 0.570444, 0.799744, 0.483437, 0.045227, 0.033580, 0.325330, 0.150950, 0.388677, 0.256953, 0.271349, 0.456034, 0.166989, 0.408733, 0.356753, 0.037308, 0.280934, 0.398215, 0.454983, 0.719483, 0.140924, 0.708244, 0.802197, 0.165267, 0.927768, 0.015636, 0.986887, 0.423401, 0.772245, 0.394881, 0.797988, 0.293488, 0.005522, 0.988485, 0.180999, 0.198842, 0.706858, 0.711342, 0.266477, 0.790176, 0.771271, 0.397337, 0.923394, 0.926301, 0.645806, 0.344651, 0.887672, 0.914960, 0.863104, 0.850039, 0.379022, 0.553114, 0.330898, 0.902270, 0.932903, 0.370818, 0.310982, 0.334698, 0.678358, 0.665923, 0.729606, 0.413217, 0.365740, 0.274722, 0.887213, 0.561243, 0.525222, 0.611581, 0.875158, 0.024749, 0.281263, 0.848914, 0.235674, 0.274729, 0.442264, 0.761695, 0.233550, 0.746496, 0.503884, 0.956272, 0.522733, 0.710663, 0.577709, 0.110890, 0.025419, 0.439336, 0.894672, 0.801578,  ),
        );

        $data = array();
        for ( $i = 0; $i < $count; ++$i )
        {
            $data[] = (int) ( $min + $cannedData[$randomize][$i] * ( $max - $min ) );
        }

        return $data;
    }
}

?>
