import React from 'react';
import { Grid, Box, Paper } from '@material-ui/core';


const LeftImage = ({classes}) => {
    return (
        <Grid item xs={12} sm={12} md={12} lg={6}>
            <Box mt={10} mb={10} className={classes.leftImage}>
                <Paper elevation={10} className={classes.paperLeftImage}/>
            </Box>
        </Grid>
    );
}
 
export default LeftImage;