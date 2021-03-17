/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow strict-local
 */

import React, {Component} from 'react';
import {
  SafeAreaView,
  StyleSheet,
  ScrollView,
  View,
  Text,
  Button,
  StatusBar,
} from 'react-native';

import {
  Header,
  LearnMoreLinks,
  Colors,
  DebugInstructions,
  ReloadInstructions,
} from 'react-native/Libraries/NewAppScreen';

import QRCodeScanner from 'react-native-qrcode-scanner';
import QRCode from 'react-native-qrcode-svg';

class App extends Component {
  state = { 
    qr: "",
    items: [],
    hashes: [],
    info: [],
    bool: -1,
    
   }
   
   onRead = e =>{
     this.setState({ qr: e.data })
     

   }
   getKeyByValue(object, value) {
    return Object.keys(object).find(key => object[key] == value);
  }
 
   async test(value)  {
    fetch('http://192.168.18.232:4000/users')
      .then(response => response.json())
      .then(users => this.setState({ items: users}, ()=> {
        //console.debug(this.state.items[0]);
       // console.debug(this.state.items[]["hash"])
        var i;
        for(i=0; i<this.state.items.length; i++){
          this.state.hashes[i]=this.state.items[i]["hash"];
        }
        console.debug(this.state.hashes);
        for(i=0; i<this.state.hashes.length; i++){
          if(this.state.info[5] == this.state.hashes[i]){
            console.debug("True");
            this.state.bool= 1;
            break;
          }
          else{
            this.state.bool= -1
          }
        }
      }
    ));
      
      //.then(users => console.debug(users))
      //var obj = this.state.items.find(o => o.hash === '4eba4e6a7ced776f5089e29833fYJBUsrLaJes9tVMb83YZNYisc2UqvY6451ee6');
      //console.debug(this.state.items);
      // console.debug(this.state.items.keys());
      // console.debug("Hello");
      // console.debug(this.getKeyByValue(this.state.items,"4eba4e6a7ced776f5089e29833fYJBUsrLaJes9tVMb83YZNYisc2UqvY6451ee6"));     

}

  render() { 
    this.state.info = (this.state.qr).split(" ");
    //this.ishash();
    return ( 
    <>
      <StatusBar barStyle="dark-content" />
      <SafeAreaView>
        <ScrollView
          contentInsetAdjustmentBehavior="automatic"
          style={styles.scrollView}>
          <QRCodeScanner
            onRead={this.onRead}
          />
          {this.state.qr ? <QRCode
            value={this.state.qr}
          /> : null}
          
          {global.HermesInternal == null ? null : (
            <View style={styles.engine}>
              <Text style={styles.footer}>Engine: Hermes</Text>
            </View>
          )}
          <View style={styles.body}>
            <View style={styles.sectionContainer}>
              
            {this.state.bool == 1 ? <Text style={styles.footer}>Verified</Text>: null }
              
              <Button onPress={ this.test.bind(this) } title=" Authenticate" />
            </View>
          </View>
        </ScrollView>
      </SafeAreaView>
    </> 
    );
  }
}
 




const styles = StyleSheet.create({
  scrollView: {
    backgroundColor: Colors.lighter,
  },
  engine: {
    position: 'absolute',
    right: 0,
  },
  body: {
    backgroundColor: Colors.white,
  },
  sectionContainer: {
    marginTop: 32,
    paddingHorizontal: 24,
  },
  sectionTitle: {
    fontSize: 24,
    fontWeight: '600',
    color: Colors.black,
  },
  sectionDescription: {
    marginTop: 8,
    fontSize: 18,
    fontWeight: '400',
    color: Colors.dark,
  },
  highlight: {
    fontWeight: '700',
  },
  footer: {
    color: Colors.dark,
    fontSize: 24,
    fontWeight: '600',
    padding: 4,
    padding: 12,
    textAlign: "center",
  },
});

export default App;
